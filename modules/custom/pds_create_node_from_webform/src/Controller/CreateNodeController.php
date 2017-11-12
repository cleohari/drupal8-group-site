<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  /**
   * Createnode.
   *
   * @return string
   *   Return Hello string.
   */
  public function createNode() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: createNode')
    ];
  }

}
