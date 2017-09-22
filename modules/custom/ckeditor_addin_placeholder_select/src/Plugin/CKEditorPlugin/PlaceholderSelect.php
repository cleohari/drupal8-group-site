<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 9/21/17
 * Time: 1:04 PM
 */

/**
 * @file
 * Definition of \Drupal\placeholderselect\Plugin\CKEditorPlugin
 */
namespace Drupal\placeholderselect\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\Core\Plugin\PluginBase;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "PlaceholderSelect" plugin.
 */
class PlaceholderSelect extends PluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface{

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginInterface::getDependencies().
   */
  function getDependencies(Editor $editor) {
    return array('basewidget');
  }

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginInterface::getLibraries().
   */
  function getLibraries(Editor $editor) {
    return array();
  }

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginInterface::isInternal().
   */
  function isInternal() {
    return FALSE;
  }

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginInterface::getFile().
   */
  function getFile() {
    return drupal_get_path('module','ckeditor_addin_placeholder_select').'/js/plugins/placeholder_select/plugin.js';
  }

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return array(
      'AddLayout'=>array(
        'label'=>t('Available Tokens'),
      ),
    );
  }

  /**
   * Implements \Drupal\ckeditor\CKEditorPluginInterface::getConfig().
   */
  public function getConfig(Editor $editor) {
    return array();
  }
}
