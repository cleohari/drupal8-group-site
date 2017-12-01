<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\group\Entity\GroupContent;
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

    // Load node entity.
    $form = Node::load($webformid);
    // Load the gorup information for the entity.
    $group = current(GroupContent::loadByEntity($form));
    /** @var \Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem $referenceItem */
    $referenceItem = $group->get('gid')->first();
    /** @var \Drupal\Core\Entity\Plugin\DataType\EntityReference $entityRef */
    $entityRef = $referenceItem->get('entity');
    /** @var \Drupal\Core\Entity\Plugin\DataType\EntityAdapter $entityAdapter */
    $entityAdapter = $entityRef->getTarget();
    /** @var \Drupal\Core\Entity\EntityInterface $referencedEntity */
    $refEntity = $entityAdapter->getValue();
    // Group ID number.
    $id = $refEntity->id();

    $stuff = 1;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Your Membership is: '),
    ];
  }
}
