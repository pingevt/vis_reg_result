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
        '1' => $this->t("1 month"),
        '3' => $this->t("3 months"),
        '6' => $this->t("6 months"),
        '12' => $this->t("12 months"),
      ],
      '#description' => $this->t(''),
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
