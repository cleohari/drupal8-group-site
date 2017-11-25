<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pds_create_node_from_webform\PDScreateNodeGroupLoader;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  /**
   * Createnode.
   *
   * @return array
   *   Return Hello string.
   */
  public function createNodePage() {
    // Call the service to get group membership information.
    $groupMemmbership = \Drupal::service('group.membership_loader');
    // Get the array of group memberships for the currentUser.
    $access = $groupMemmbership->loadByUser($this->currentUser());
    // Array for group IDs.
    $gid = [];
    foreach ($access as $membership) {
      $single = $membership->getGroup();
      $gid[] = $single->id();
    }
    $stuff = 1;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Your Membership is: ' . print_r($gid)),
    ];
  }
}
