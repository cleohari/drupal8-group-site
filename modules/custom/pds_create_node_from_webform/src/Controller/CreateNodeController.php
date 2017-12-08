<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\group\Entity\Group;
use Drupal\group\Entity\GroupContent;
use Drupal\node\Entity\Node;
use joshtronic\LoremIpsum;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class CreateNodeController.
 */
class CreateNodeController extends ControllerBase {

  /**
   * Create a node from a webform.
   *
   * @param int $webformid
   *  Webform Node ID.
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
    // Load the group information for the entity.
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
      $content = $lipsom->paragraphs(3, 'p');

      $node = Node::create([
        'type' => 'plan_document',
        'field_questionformid' => $webformid,
        'title' => t('New Document'),
      ]);
      $node->set('field_document_sections', [
        'format' => 'full_html',
        'value' => $content,
      ]);

      $node->save();
      $newnodeid = $node->id();
      // Load the new node Entity.
      $newnodeinstance = Node::load($newnodeid);

      // Load group and add our newly created node to it.
      $group = \Drupal::entityTypeManager()->getStorage('group')->load($id);
      $group->addContent($newnodeinstance, 'group_node:plan_document');

      $response = new RedirectResponse("/node/$newnodeid/edit");
      $response->send();
    }
    else {
      $build = [];
      $build['#title'] = $this->t('Closed dooors. Do not pass go.');
      $build['#markup'] = $this->t('You account does not permit you access to this information.');
      return $build;
    }
  }
}
