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

  public function __construct(StateInterface $state) {
    $this->state = $state;
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
   * Returns the current emergency status.
   *
   * @return boolean
   *   TRUE if the user-specified emergency status is set, FALSE otherwise.
   */
  public function getStatus() {
    return $this->state->get('emergency_block.status', FALSE);
  }

  /**
   * Determines if the site is in emergency mode.
   *
   * @return boolean
   *   TRUE if the site is in emergency mode, FALSE otherwise.
   */
  public function isEmergency() {
    return $this->state->get('emergency_block.status');
  }

  /**
   *
   * @return string
   *   A machine name for the reason the site is in emergency status, or FALSE
   *   if it's not.
   */
  public function getReason() {
    if ($this->state->get('emergency_block.status')) {
      return 'admin';
    }
    return FALSE;
  }

  /**
   * Returns the site's emergency status message.
   *
   * @return string
   *   The short emergency message.
   */
  public function getMessage() {
    return $this->state->get('emergency_block.message');
  }

  /**
   * Returns the current emergency status message to display.
   *
   * @return string
   *   The short emergency message, if enabled.
   */
  public function getCurrentMessage() {
    if ($this->isEmergency()) {
      return $this->state->get('emergency_block.message');
    }

    return '';
  }

  /**
   * Returns the site's detailed emergency status message.
   *
   * @return string
   *   The short emergency message.
   */
  public function getDetailedMessage() {
    return $this->state->get('emergency_block.detailed_message');
  }

  /**
   * Returns the detailed message's text format.
   *
   * @return string
   *   The machine name of the detailed message format.
   */
  public function getDetailedMessageFormat() {
    return $this->state->get('emergency_block.detailed_message_format');
  }

  /**
   * Sets the short message for the site's emergency status.
   *
   * @param string $message
   *   The message to set.
   * @return static
   */
  public function setMessage($message) {
    $this->state->set('emergency_block.message', $message);
    return $this;
  }

  /**
   * Sets the detailed message for the site's emergency status.
   *
   * @param string $message
   *   The message to set.
   * @return static
   */
  public function setDetailedMessage($message, $format) {
    $this->state->set('emergency_block.detailed_message', $message);
    $this->state->set('emergency_block.detailed_message_format', $format);
    return $this;
  }

}
