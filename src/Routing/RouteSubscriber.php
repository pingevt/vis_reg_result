<?php

namespace Drupal\vis_reg_result\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Route subscriber class.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[RoutingEvents::ALTER] = ['onAlterRoutes', -1025];
    return $events;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $vis_reg_result_can = $collection->get('entity.vis_reg_result.canonical');
    // Change canonical to admin route.
    $vis_reg_result_can->addOptions(
      [
        '_admin_route' => 'TRUE',
      ]
    );
  }

}
