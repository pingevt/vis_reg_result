<?php

namespace Drupal\vis_reg_result\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Defines a route controller for ...
 */
class Api extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FileSystemInterface $file_system, LoggerChannelFactoryInterface $factory, ConfigFactoryInterface $config_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->fileSystem = $file_system;
    $this->loggerFactory = $factory;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('file_system'),
      $container->get('logger.factory'),
      $container->get('config.factory')
    );
  }

  /**
   * Create Vis Reg entity on API call.
   */
  public function visRegResults(Request $request) {

    $data = [
      "status" => "OK",
      "data" => [],
    ];

    // Check params are here.
    $project = $request->request->get("project");
    $branch = $request->request->get("branch");

    if (empty($project) || empty($branch)) {
      $bad_response = new BadRequestHttpException("project and branch variables need to be set!");
      throw $bad_response;
    }

    $config = $this->configFactory->get('system.date');
    $config_data_default_timezone = $config->get('timezone.default');

    $now = new \Datetime();
    $now->setTimezone(new \DateTimezone($config_data_default_timezone));
    $timestamp = $now->format("Ymd-His");
    $now->setTimezone(new \DateTimezone("UTC"));

    // Create new vis_reg_result.
    $vis_reg_result_storage = $this->entityTypeManager->getStorage('vis_reg_result');

    $data['data']['project'] = $project;
    $data['data']['branch'] = $branch;
    $data['data']['timestamp'] = $timestamp;

    $report = $vis_reg_result_storage->create([
      'label' => $project . ":" . $branch . ":" . $timestamp,
      'status' => TRUE,
      'field_project' => $project,
      'field_branch' => $branch,
      'field_timestamp' => $now->format("Y-m-d\TH:i:s"),
      'field_directory' => $timestamp,
    ]);

    $report->save();

    $data['data']['id'] = $report->id();

    // Create upload directory.
    // @todo this should be set in a settings file.
    $target_dir = "public://vis-reg-reports/" . $timestamp;
    $this->fileSystem->prepareDirectory($target_dir, FileSystemInterface::CREATE_DIRECTORY);

    // Log it all!
    $this->loggerFactory->get('vis_reg_result_api')->info("<pre>" . print_r($data['data'], TRUE) . "</pre>", []);

    $response = new JsonResponse($data);

    return $response;
  }

}
