<?php
namespace Drupal\pds_create_node_from_webform;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\group\GroupMembershipLoaderInterface;

class PDScreateNodeGroupLoader implements GroupMembershipLoaderInterface {

  public function load(GroupInterface $group, AccountInterface $account) {
    // TODO: Implement load() method.
  }

  public function loadByGroup(GroupInterface $group, $roles = NULL) {
    // TODO: Implement loadByGroup() method.
  }

  public function loadByUser(AccountInterface $account = NULL, $roles = NULL) {
    // TODO: Implement loadByUser() method.
  }

}
