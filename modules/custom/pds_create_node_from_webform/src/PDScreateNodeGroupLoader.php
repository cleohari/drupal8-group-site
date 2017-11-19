<?php

namespace Drupal\pds_create_node_from_webform;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\group\GroupMembershipLoaderInterface;

class PDScreateNodeGroupLoader implements GroupMembershipLoaderInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user's account object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */

  protected $currentUser;


  /**
   * Constructs a new PDScreateNodeGroupLoader.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, AccountInterface $current_user) {
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  public function load(GroupInterface $group, AccountInterface $account) {
    // TODO: Implement load() method.
  }

  public function loadByGroup(GroupInterface $group, $roles = NULL) {
    // TODO: Implement loadByGroup() method.
  }

  /**
   * Load Group permissions based on a user.
   *
   * @param \Drupal\Core\Session\AccountInterface|NULL $account
   * @param null $roles
   *
   * @return array|\Drupal\group\GroupMembership[]
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public function loadByUser(AccountInterface $account = NULL, $roles = NULL) {
    if (!isset($account)) {
      $account = $this->currentUser;
    }

    // Load all group content types for the membership content enabler plugin.
    $group_content_types = $this->entityTypeManager
      ->getStorage('group_content_type')
      ->loadByProperties(['content_plugin' => 'group_membership']);

    // If none were found, there can be no memberships either.
    if (empty($group_content_types)) {
      return [];
    }

    // Try to load all possible membership group content for the user.
    $group_content_type_ids = [];
    foreach ($group_content_types as $group_content_type) {
      $group_content_type_ids[] = $group_content_type->id();
    }

    $properties = [
      'type' => $group_content_type_ids,
      'entity_id' => $account->id(),
    ];
    if (isset($roles)) {
      $properties['group_roles'] = (array) $roles;
    }

    /** @var \Drupal\group\Entity\GroupContentInterface[] $group_contents */
    $group_contents = $this->groupContentStorage()
      ->loadByProperties($properties);
    return $this->wrapGroupContentEntities($group_contents);
  }

}
