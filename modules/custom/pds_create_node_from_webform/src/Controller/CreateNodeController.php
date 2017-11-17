<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
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
  private $currentUser;

  /**
   * Constructs a new CreateNodeController.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }


  /**
   * Createnode.
   *
   * @return array
   *   Return Hello string.
   */
  public function createNodePage() {
    $groupMemmberShip = new GroupMembershipLoader();





    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: createNode'),
    ];
  }

}
