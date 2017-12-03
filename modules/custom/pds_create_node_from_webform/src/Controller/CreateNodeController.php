<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\group\Entity\GroupContent;
use Drupal\node\Entity\Node;
use joshtronic\LoremIpsum;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  private $lorem;

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
    $usergids = [];
    foreach ($access as $membership) {
      $usergids[] = $membership->getGroup()->id();
    }

    // @TODO contribute a service wrapper to Group module for this.

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

    if (in_array($id, $usergids)) {
      $lipsom = new LoremIpsum();
      $content = $lipsom->paragraphs(4);

      $node = Node::create([
        'type' => 'plan_document',
        'questionformid' => $webformid,
        'title' => t('New Document'),
        'document_sections' => [
          ['value' => $content],
        ],
      ]);
      $node->save();
      $newnodeid = $node->id();
      $stuff = 1;
      return [
        '#type' => 'markup',
        '#markup' => $this->t('Your Membership is: '),
      ];
    }
    else {
      $build = [];
      $build['#title'] = t('Closed dooors. Do not pass go.');

      return [
        '#type' => 'markup',
        '#markup' => $this->t('You account does not permit you access to this information.'),
      ];

    }


  }
}
