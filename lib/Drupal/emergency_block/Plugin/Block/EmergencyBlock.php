<?php

namespace Drupal\emergency_block\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Component\Utility\Xss;
use Drupal\emergency_block\EmergencyStatus;

/**
 * Provides an emergency block.
 *
 * @Block(
 *   id = "emergency_block_message",
 *   admin_label = @Translation("Emergency message block"),
 *   category = @Translation("System")
 * )
 */
class EmergencyBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The emergency service.
   *
   * @var \Drupal\emergency_block\EmergencyStatus
   */
  protected $emergency;

  /**
   * Constructs a new EmergencyBlock.
   *
   * @param array $configuration
   * @param type $plugin_id
   * @param array $plugin_definition
   * @param \Drupal\Core\KeyValueStore\StateInterface $state
   *   The state service.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, EmergencyStatus $emergency) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->emergency = $emergency;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('emergency_block.status')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    return $this->emergency->isEmergency();
  }

  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build() {
    $message = $this->emergency->getCurrentMessage();
    $message = Xss::filterAdmin($message);

    $return = [
      '#theme' => 'emergency_block__' . $this->emergency->getReason(),
      '#message' => $message,
      '#reason' => $this->emergency->getReason(),
    ];
    $return['#attached']['css'] = array(
      drupal_get_path('module', 'emergency_block') . '/emergency_block.css',
    );

    return $return;
  }

}
