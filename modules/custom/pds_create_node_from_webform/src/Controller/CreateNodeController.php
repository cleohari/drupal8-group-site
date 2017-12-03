<?php

namespace Drupal\pds_create_node_from_webform\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\group\Entity\GroupContent;
use Drupal\node\Entity\Node;
use joshtronic\LoremIpsum;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
      $content = $lipsom->paragraphs(3, 'p');

      $node = Node::create([
        'type' => 'plan_document',
        'questionformid' => $webformid,
        'title' => t('New Document'),
      ]);
      $node->set('field_document_sections', [
        'format' => 'full_html',
        'value' => $content,
      ]);

      $node->save();
      $newnodeid = $node->id();
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
