<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  /**
   * Createnode.
   *
   * @return array
   */
  public function createNodePage($webformid) {
    // Call the service to get group membership information.
    $groupMemmbership = \Drupal::service('group.membership_loader');
    // Get the array of group memberships for the currentUser.
    $access = $groupMemmbership->loadByUser($this->currentUser());
    // Array for group IDs.
    $gid = [];
    foreach ($access as $membership) {
      $gid[] = $membership->getGroup()->id();
    }

    $form = Node::load($webformid);

    // Load all Webforms.
    // $nids = \Drupal::entityQuery('node')->condition('type','webform')->execute();

    $accessCheckService = \Drupal::service('access_check.group.owns_content');


    $stuff = 1;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Your Membership is: '),
    ];
  }
}
