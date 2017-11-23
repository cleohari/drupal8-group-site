<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxy;
use Drupal\group\GroupMembershipLoader;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new CreateNodeController.
   *
   * @param \Drupal\Core\Session\AccountProxy $current_user
   *   The current user.
   */
  public function __construct() {

  }

  /**
   * Createnode.
   *
   * @return array
   *   Return Hello string.
   */
  public function createNodePage() {
    $groupMemmbership = new GroupMembershipLoader();
    // Get the array of group memberships for the currentUser.
    $access = $groupMemmbership->loadByUser($this->currentUser, []);

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Your Membership is: ' . print_r($access)),
    ];
  }
}
