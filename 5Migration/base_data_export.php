<?php

use \Drupal\node\Entity\Node;

$node_id = 61;
$serializer = \Drupal::service('serializer');
$node = Node::load($node_id);
$data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
file_put_contents('/bsw-sites/pds142/5Migration/content/entplus/'.$node_id.'.json',$data);