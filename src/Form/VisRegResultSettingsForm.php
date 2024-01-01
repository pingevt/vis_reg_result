<?php

namespace Drupal\vis_reg_result\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for a vis-reg result entity type.
 */
class VisRegResultSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'vis_reg_result_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['vis_reg_result.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('vis_reg_result.settings');

    $form['settings'] = [
      '#markup' => $this->t('Settings form for a vis-reg result entity type.'),
    ];

    $form['timespan'] = [
      '#type' => 'select',
      '#title' => $this->t('How long to keep these entities'),
      '#default_value' => $config->get('timespan'),
      '#options' => [
        '1' => "1 month",
        '3' => "3 months",
        '6' => "6 months",
        '12' => "12 months",
      ],
      '#description' => $this->t('The maximum number of messages to keep in the database log. Requires a <a href=":cron">cron maintenance task</a>.', [':cron' => Url::fromRoute('system.status')->toString()]),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('vis_reg_result.settings')
      ->set('timespan', $form_state->getValue('timespan'))
      ->save();

    parent::submitForm($form, $form_state);

    $this->messenger()->addStatus($this->t('The configuration has been updated.'));
  }

}
