<?php

namespace Drupal\emergency_block\Form;

use Drupal\Core\Form\FormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\KeyValueStore\StateInterface;

/**
 * Configuration form for the emergency block status.
 */
class EmergencyBlockForm extends FormBase {

  /**
   * The state API store.
   *
   * @var \Drupal\Core\KeyValueStore\StateInterface
   */
  protected $state;

  /**
   * Creates a new EmergencyBlockForm.
   *
   * @param \Drupal\Core\KeyValueStore\StateInterface $state
   *   The state service.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('state'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'emergency_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show emergency block'),
      '#description' => $this->t('If checked, the emergency block will be shown.'),
      '#default_value' => $this->state->get('emergency_block.status'),
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Emergency message'),
      '#description' => $this->t('The message to display when this block is enabled.'),
      '#default_value' => $this->state->get('emergency_block.message'),
    ];

    $form['detailed_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Detailed message'),
      '#description' => $this->t('A more detailed message to display on a dedicated page.'),
      '#default_value' => $this->state->get('emergency_block.detailed_message'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $this->state->set('emergency_block.status', $form_state['values']['status']);
    $this->state->set('emergency_block.message', $form_state['values']['message']);
    $this->state->set('emergency_block.detailed_message', $form_state['values']['detailed_message']);
  }
}
