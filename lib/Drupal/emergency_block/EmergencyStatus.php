<?php

namespace Drupal\emergency_block;

use Drupal\Core\KeyValueStore\StateInterface;

/**
 * Domain object for the emergency status of the site.
 */
class EmergencyStatus {

  /**
   * The state service.
   *
   * @var \Drupal\Core\KeyValueStore\StateInterface
   */
  protected $state;

  /**
   * The weather service
   *
   * @var \Drupal\emergency_block\Weather
   */
  protected $weather;

  public function __construct(StateInterface $state, Weather $weather) {
    $this->state = $state;
    $this->weather = $weather;
  }

  /**
   * Sets the current emergency status.
   *
   * @param boolean $status
   *   TRUE if we're in an emergency situation, FALSE otherwise.
   * @return static
   */
  public function setStatus($status) {
    $this->state->set('emergency_block.status', $status);
    return $this;
  }

  /**
   * Determines if the site is in emergency mode.
   *
   * @return boolean
   *   TRUE if the site is in emergency mode, FALSE otherwise.
   */
  public function isEmergency() {
    return $this->weather->isTooCold() || $this->state->get('emergency_block.status');
  }

  public function getMessage() {
    if ($this->isEmergency()) {
      return $this->state->get('emergency_block.status') ? $this->state->get('emergency_block.message') : $this->config->get('message');
    }
    return '';
  }

  public function setMessage($message) {
    $this->state->set('emergency_block.message', $message);
    return $this;
  }

  public function setDetailedMessage($message) {
    $this->state->set('emergency_block.detailed_message', $message);
    return $this;
  }

}
