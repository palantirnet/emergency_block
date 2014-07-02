<?php

namespace Drupal\emergency_block\Form;

use Drupal\Core\Form\FormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\emergency_block\EmergencyStatus;

/**
 * Configuration form for the emergency block status.
 */
class EmergencyBlockForm extends FormBase {

  /**
   * The emergency service.
   *
   * @var \Drupal\emergency_block\EmergencyStatus
   */
  protected $emergency;

  /**
   * Creates a new EmergencyBlockForm.
   */
  public function __construct(EmergencyStatus $emergency) {
    $this->emergency = $emergency;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('emergency_block.status'));
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
      '#default_value' => $this->emergency->getStatus(),
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Emergency message'),
      '#description' => $this->t('The message to display when this block is enabled.'),
      '#default_value' => $this->emergency->getMessage(),
    ];

    $form['detailed_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Detailed message'),
      '#description' => $this->t('A more detailed message to display on a dedicated page.'),
      '#default_value' => $this->emergency->getDetailedMessage(),
      '#format' => $this->emergency->getDetailedMessageFormat(),
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

    $this->emergency
      ->setStatus($form_state['values']['status'])
      ->setMessage($form_state['values']['message'])
      ->setDetailedMessage($form_state['values']['detailed_message']['value'], $form_state['values']['detailed_message']['format']);
  }
}
