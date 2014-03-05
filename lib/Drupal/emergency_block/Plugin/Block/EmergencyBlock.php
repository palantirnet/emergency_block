<?php

namespace Drupal\emergency_block\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\KeyValueStore\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\emergency_block\Weather;
use Drupal\Core\Config\Config;

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
   * The state API store.
   *
   * @var \Drupal\Core\KeyValueStore\StateInterface
   */
  protected $state;

  /**
   * The weather service.
   *
   * @var \Drupal\emergency_block\Weather
   */
  protected $weather;

  /**
   * The config object for the emergeny weather service.
   *
   * @var \Drupal\Core\Config
   */
  protected $config;

  /**
   * Constructs a new EmergencyBlock.
   *
   * @param array $configuration
   * @param type $plugin_id
   * @param array $plugin_definition
   * @param \Drupal\Core\KeyValueStore\StateInterface $state
   *   The state service.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, StateInterface $state, Weather $weather, Config $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->state = $state;
    $this->weather = $weather;
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, array $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state'),
      $container->get('emergency_block.weather'),
      $container->get('config.factory')->get('emergency_block.weather')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    return $this->weather->isTooCold() || $this->state->get('emergency_block.status');
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
    $message = $this->state->get('emergency_block.status') ? $this->state->get('emergency_block.message') : $this->config->get('message');

    $return = [
      '#theme' => 'emergency_block',
      '#message' => $message,
      '#weather' => $this->weather->isTooCold(),
      '#emergency_status' => $this->state->get('emergency_block.status'),
    ];
    $return['#attached']['css'] = array(
      drupal_get_path('module', 'emergency_block') . '/emergency_block.css',
    );

    return $return;
  }

}
