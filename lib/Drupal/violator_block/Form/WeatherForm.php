<?php

namespace Drupal\violator_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactory;

/**
 * Description of WeatherForm
 *
 * @author garfield
 */
class WeatherForm extends ConfigFormBase {

  /**
   * The configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a WeatherForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactory $config_factory) {
    parent::__construct($config_factory);
    $this->config = $this->config('violator_block.weather');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'violator_block_weather';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $form['weather_station'] = array(
      '#title' => t('Weather station'),
      '#description' => t('The weather station to query'),
      '#type' => 'textfield',
      '#default_value' => $this->config->get('weather_station'),
    );

    $form['threshold'] = array(
      '#title' => t('Temperature threshold'),
      '#description' => t('How cold it needs to be before we automatically close.'),
      '#type' => 'number',
      '#step' => 1,
      '#default_value' => $this->config->get('threshold'),
    );

    $form['message'] = array(
      '#title' => t('Message to display'),
      '#description' => t('The message to show when it is too cold'),
      '#type' => 'textarea',
      '#default_value' => $this->config->get('message'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    parent::submitForm($form, $form_state);

    foreach (array('weather_station', 'threshold', 'message') as $key) {
      $this->config->set($key, $form_state['values'][$key]);
    }

    $this->config->save();
  }

}
