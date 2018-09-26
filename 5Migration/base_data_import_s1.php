<?php
define('DRUPAL_DIR', getcwd());
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;
// Specify relative path to the drupal root.
$autoloader = require_once DRUPAL_DIR . '/autoload.php';
$request = Request::createFromGlobals();
$kernel = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();

require_once DRUPAL_DIR . '/core/includes/database.inc';
require_once DRUPAL_DIR . '/core/includes/schema.inc';
require_once DRUPAL_DIR . '/core/includes/file.inc';
$_SERVER['SCRIPT_NAME'] = '/s1.pds.l/index.php';

print_r("Drupal Directory: " . DRUPAL_DIR . "\n");

$files = array_slice(scandir(getcwd() . '/5Migration/content/entplus/node', 0), 2);
print_r("Starting content creation!\n");
foreach ($files as $file) {
  $json = file_get_contents(getcwd() . '/5Migration/content/entplus/node/' . $file);
  $json_data = json_decode($json, TRUE);

  // print_r("JSON Data\n" . $json_data . "\n");
  // @todo fix this.
  // The script breaks here.
  $node = Node::create(['type' => $json_data['type'][0]['target_id']]);
  $node->set('title', $json_data['title'][0]['value']);


  //Body can now be an array with a value and a format.
  //If body field exists.
  if ($json_data['body'][0]['value'] != NULL) {
    $body = [
      'value' => character_replacement($json_data['body'][0]['value']),
      'format' => $json_data['body'][0]['format'],
    ];
    $node->set('body', $body);
  }

  //If path field exists.
  if ($json_data['path'] != NULL) {
    $path = [
      'path' => $json_data['path'][0]['alias'],
    ];
    $node->set('path', $path);
  }

  if ($json_data['promote'] != NULL) {
    $promote = [
      'value' => $json_data['promote'][0]['value'],
    ];
    $node->set('promote', $promote);
  }

  if ($json_data['sticky'] != NULL) {
    $sticky = [
      'value' => $json_data['sticky'][0]['value'],
    ];
    $node->set('sticky', $sticky);
  }

  if ($json_data['status'] != NULL) {
    $status = [
      'value' => $json_data['status'][0]['value'],
    ];
    $node->set('status', $status);
  }
  //</editor-fold>

  //<editor-fold desc="Article content settings">
  //</editor-fold>

  //<editor-fold desc="Basic Page content settings">
  //</editor-fold>

  //<editor-fold desc="Benefit content settings">
  if ($json_data['field_mt_font_awesome_classes'] != NULL) {
    $field_mt_font_awesome_classes = [
      'value' => $json_data['field_mt_font_awesome_classes'][0]['value'],
    ];
    $node->set('field_mt_font_awesome_classes', $field_mt_font_awesome_classes);
  }

  if ($json_data['field_mt_subheader_body'] != NULL) {
    $field_mt_subheader_body = [
      'value' => $json_data['field_mt_subheader_body'][0]['value'],
      'format' => $json_data['field_mt_subheader_body'][0]['format'],
      'summary' => $json_data['field_mt_subheader_body'][0]['summary'],
    ];
    $node->set('field_mt_subheader_body', $field_mt_subheader_body);
  }

  if ($json_data['field_mt_video'] != NULL) {
    $field_mt_video = [
      'value' => $json_data['field_mt_video'][0]['value'],
    ];
    $node->set('field_mt_video', $field_mt_video);
  }

  //If field_mt_bg_video_youtube field exists.
  if ($json_data['field_mt_bg_video_youtube'] != NULL) {
    $field_mt_bg_video_youtube = [
      'value' => $json_data['field_mt_bg_video_youtube'][0]['value'],
    ];
    $node->set('field_mt_bg_video_youtube', $field_mt_bg_video_youtube);
  }

  //If field_mt_bg_video_volume field exists.
  if ($json_data['field_mt_bg_video_volume'] != NULL) {
    $field_mt_bg_video_volume = [
      'value' => $json_data['field_mt_bg_video_volume'][0]['value'],
    ];
    $node->set('field_mt_bg_video_volume', $field_mt_bg_video_volume);
  }

  //If field_mt_slideshow field exists.
  if ($json_data['field_mt_slideshow'] != NULL) {
    $field_mt_slideshow = [
      'value' => $json_data['field_mt_slideshow'][0]['value'],
    ];
    $node->set('field_mt_slideshow', $field_mt_slideshow);
  }
  //</editor-fold>

  //<editor-fold desc="Team Member content settings">
  if ($json_data['field_mt_facebook_account'] != NULL) {
    $field_mt_facebook_account = [
      'value' => $json_data['field_mt_facebook_account'][0]['value'],
    ];
    $node->set('field_mt_facebook_account', $field_mt_facebook_account);
  }

  if ($json_data['field_mt_linkedin_account'] != NULL) {
    $field_mt_linkedin_account = [
      'value' => $json_data['field_mt_linkedin_account'][0]['value'],
    ];
    $node->set('field_mt_linkedin_account', $field_mt_linkedin_account);
  }

  if ($json_data['field_mt_twitter_account'] != NULL) {
    $field_mt_twitter_account = [
      'value' => $json_data['field_mt_twitter_account'][0]['value'],
    ];
    $node->set('field_mt_twitter_account', $field_mt_twitter_account);
  }
  //</editor-fold>

  //<editor-fold desc="Testimonial content settings">
  if ($json_data['field_mt_subtitle'] != NULL) {
    $field_mt_subtitle = [
      'value' => $json_data['field_mt_subtitle'][0]['value'],
    ];
    $node->set('field_mt_subtitle', $field_mt_subtitle);
  }
  //</editor-fold>

  //<editor-fold desc=End Date content settings">
  if ($json_data['field_pds_alert_type'] != NULL) {
    $field_pds_alert_type = [
      'value' => $json_data['field_pds_alert_type'][0]['value'],
    ];
    $node->set('field_pds_alert_type', $field_pds_alert_type);
  }
  //</editor-fold>

  //<editor-fold desc=Important Flag content settings">
  if ($json_data['field_pds_important_flag'] != NULL) {
    $field_pds_important_flag = [
      'value' => $json_data['field_pds_important_flag'][0]['value'],
    ];
    $node->set('field_pds_important_flag', $field_pds_important_flag);
  }
  //</editor-fold>

  //<editor-fold desc=Start Date content settings">
  if ($json_data['field_pds_start_date'] != NULL) {
    $field_pds_start_date = [
      'value' => $json_data['field_pds_start_date'][0]['value'],
    ];
    $node->set('field_pds_start_date', $field_pds_start_date);
  }
  //</editor-fold>

  //<editor-fold desc=End Date content settings">
  if ($json_data['field_pds_end_date'] != NULL) {
    $field_pds_end_date = [
      'value' => $json_data['field_pds_end_date'][0]['value'],
    ];
    $node->set('field_pds_end_date', $field_pds_end_date);
  }


  $node->set('uid', 1);
  $node->status = 1;
  $node->enforceIsNew();
  $node->save();
  print_r("Node with nid " . $node->id() . " saved!\n");
}
print_r("Finished content creation!\n");


function character_replacement($str) {
  $search_char = [
    "&lt;",
    "&gt;",
    '&amp;',
    '&#039;',
    '&quot;',
    '&lt;',
    '&gt;',
    '&#038;',
    '&#8211;',
    '&#8212;',
    '&#8217;',
    '&#8220;',
    '&#8221;',
    '&#8230;',
  ];
  $replace_char = [
    "<",
    ">",
    '&',
    '\'',
    '"',
    '<',
    '>',
    '&',
    '–',
    '—',
    '\'',
    '“',
    '”',
    '…',
  ];
  $str = str_replace($search_char, $replace_char, htmlspecialchars_decode($str, ENT_NOQUOTES));
  return $str;
}