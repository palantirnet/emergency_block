<?php

namespace Drupal\emergency_block\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\emergency_block\EmergencyStatus;
use Drupal\Core\Access\AccessInterface;

/**
 * Controllers for the Emergency Block module.
 */
class EmergencyController extends ControllerBase {

  /**
   * The emergency service.
   *
   * @var \Drupal\emergency_block\EmergencyStatus
   */
  protected $emergency;

  /**
   * Constructs a new EmergencyController.
   *
   * @param \Drupal\emergency_block\EmergencyStatus $emergency
   *   The emergency service.
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
   * Access callback; Allow access only if the site is in emergency mode.
   */
  public function access() {
    return $this->emergency->isEmergency() ? AccessInterface::ALLOW : AccessInterface::DENY;
  }

  /**
   * Returns the detailed status message.
   *
   * @return array
   *   A render array for the page body.
   */
  public function page() {
    $content = check_markup($this->emergency->getDetailedMessage(), $this->emergency->getDetailedMessageFormat());

    return [
      '#markup' => $content,
    ];

  }
}
