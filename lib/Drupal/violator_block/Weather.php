<?php

namespace Drupal\violator_block;

use Guzzle\Http\Client;
use Drupal\Core\KeyValueStore\StateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Configuration form for the weather option of the violator.
 */
class Weather {

  /**
   * The Guzzle HTTP client.
   *
   * @var \Guzzle\Http\Client
   */
  protected $guzzle;

  /**
   * The state API store.
   *
   * @var \Drupal\Core\KeyValueStore\StateInterface
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
   * @param \Guzzle\Http\Client $guzzle
   *   The HTTP client.
   * @param \Drupal\Core\KeyValueStore\StateInterface $state
   *   The State API store.
   * @param string $url
   *   The base URL from which to request weather data.
   */
  public function __construct(Client $guzzle, StateInterface $state, ConfigFactoryInterface $config_factory, $url) {
    $this->guzzle = $guzzle;
    $this->state = $state;
    $this->config = $config_factory->get('violator_block.weather');
    $this->url = $url;
  }

  /**
   * Updates the current temperature
   */
  public function updateTemperature() {
    try {
      $response = $this->guzzle->get($this->url . $this->config->get('weather_station'))->send();
      $json = $response->json();

      $temp = $json['main']['temp'];

      $this->state->set('violator_block.weather.temp', $temp);
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
    $temp = $this->state->get('violator_block.weather.temp');
    // We're not setting a default, so the default default is NULL. In that case
    // it's not too cold.
    if (!is_null($temp) && $temp <= $this->config->get('threshold')) {
      return TRUE;
    }
    return FALSE;
  }

}
