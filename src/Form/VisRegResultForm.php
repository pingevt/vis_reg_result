<?php

namespace Drupal\vis_reg_result\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the vis-reg result entity edit forms.
 */
class VisRegResultForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New vis-reg result %label has been created.', $message_arguments));
        $this->logger('vis_reg_result')->notice('Created new vis-reg result %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The vis-reg result %label has been updated.', $message_arguments));
        $this->logger('vis_reg_result')->notice('Updated vis-reg result %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.vis_reg_result.canonical', ['vis_reg_result' => $entity->id()]);

    return $result;
  }

}
