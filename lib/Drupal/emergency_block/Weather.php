<?php

namespace Drupal\emergency_block;

use Drupal\Core\Http\Client;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Configuration form for the weather option of the emergency block.
 */
class Weather {

  /**
   * The HTTP client.
   *
   * @var \Drupal\Core\Http\Client
   */
  protected $http_client;

  /**
   * The state API store.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The base URl from which to request data.
   *
   * @var string
   */
  protected $url;

  /**
   * Constructs a new Weather object.
   *
   * @param \Drupal\Core\Http\Client $http_client
   *   The HTTP client.
   * @param \Drupal\Core\State\StateInterface $state
   *   The State API store.
   * @param string $url
   *   The base URL from which to request weather data.
   */
  public function __construct(Client $http_client, StateInterface $state, ConfigFactoryInterface $config_factory, $url) {
    $this->http_client = $http_client;
    $this->state = $state;
    $this->config = $config_factory->get('emergency_block.weather');
    $this->url = $url;
  }

  /**
   * Updates the current temperature
   */
  public function updateTemperature() {
    try {
      $response = $this->http_client->get($this->url . $this->config->get('weather_station'));
      $json = $response->json();

      $temp = $json['main']['temp'];

      $this->state->set('emergency_block.weather.temp', $temp);
    }
    catch (\Exception $e) {
      // @todo Error handling.
    }
  }

  /**
   * Determines if it's too cold to come to school.
   *
   * @return boolean
   *   TRUE if the temperature is below a configured threshold, FALSE otherwise.
   */
  public function isTooCold() {
    $temp = $this->state->get('emergency_block.weather.temp');
    // We're not setting a default, so the default default is NULL. In that case
    // it's not too cold.
    if (!is_null($temp) && $temp <= $this->config->get('threshold')) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Returns the message to display when it's too cold.
   */
  public function getMessage() {
    return $this->config->get('message');
  }

}
