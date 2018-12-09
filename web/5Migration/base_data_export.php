<?php

use \Drupal\node\Entity\Node;

$node_id = 64;
$serializer = \Drupal::service('serializer');
$node = Node::load($node_id);
$data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
file_put_contents('/bsw-sites/pds143/5Migration/content/entplus/node/'.$node_id.'.json',$data);