<?php

namespace Drupal\violator_block;

use Drupal\block\BlockBase;
use Drupal\Core\Session\AccountInterface;;

/**
 * Description of EmergencyViolatorBlock
 *
 * @author garfield
 */
class EmergencyViolatorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    // Replace with special visibilty logic based on the weather service and
    // the configured state.
    return TRUE;
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
    return 'Hello world';
  }

}
