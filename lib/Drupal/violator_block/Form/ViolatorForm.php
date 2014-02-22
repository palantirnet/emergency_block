<?php

namespace Drupal\violator_block;

use Drupal\Core\Form\FormBase;

/**
 * Description of ViolatorForm
 *
 * @author garfield
 */
class ViolatorForm extends FormBase {

  // @todo this needs the state system, since it saves to that rather than config.

  public function getFormId() {
    return 'violator_block_form';
  }

  public function buildForm(array $form, array &$form_state) {

  }

  public function submitForm(array &$form, array &$form_state) {

  }
}
