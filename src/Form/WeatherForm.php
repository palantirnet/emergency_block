<?php

namespace Drupal\emergency_block\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * The state API store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a WeatherForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactory $config_factory, StateInterface $state) {
    parent::__construct($config_factory);
    $this->config = $this->config('emergency_block.weather');
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'emergency_block_weather';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['weather_station'] = array(
      '#title' => $this->t('Weather station'),
      '#description' => $this->t('The weather station to query'),
      '#type' => 'textfield',
      '#default_value' => $this->config->get('weather_station'),
    );

    $form['threshold'] = array(
      '#title' => $this->t('Temperature threshold'),
      '#description' => $this->t('How cold it needs to be before we automatically close.'),
      '#type' => 'number',
      '#step' => 1,
      '#default_value' => $this->config->get('threshold'),
    );

    $form['current'] = array(
      '#markup' => $this->t('Current temperature: %temp F', ['%temp' => $this->state->get('emergency_block.weather.temp', $this->t('No data available'))]),
    );

    $form['message'] = array(
      '#title' => t('Message to display'),
      '#description' => $this->t('The message to show when it is too cold.'),
      '#type' => 'textarea',
      '#default_value' => $this->config->get('message'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    foreach (array('weather_station', 'threshold', 'message') as $key) {
      $this->config->set($key, $form_state->getValue($key));
    }

    $this->config->save();
  }

}
