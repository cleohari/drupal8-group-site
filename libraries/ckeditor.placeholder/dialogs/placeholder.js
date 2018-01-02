/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * @fileOverview Definition for placeholder plugin dialog.
 *
 */

'use strict';

CKEDITOR.dialog.add('placeholder', function (editor) {
  var lang = editor.lang.placeholder,
    generalLabel = editor.lang.common.generalTab,
    validNameRegex = /^[^\[\]<>]+$/;

  // PDS Custom code to make replacements from Webform.
  var elements = new Array();
  elements = drupalSettings.ckeditoraddinplaceholder.questionTokenNames;
  var arr = new Array();

  for (var i = 0; i < elements.length; i++) {
    arr[i] = new Array();
    arr[i][0] = elements[i];
  }
  // End custom PDS code.

  return {
    title: lang.title,
    minWidth: 300,
    minHeight: 80,
    contents: [
      {
        id: 'info',
        label: generalLabel,
        title: generalLabel,
        elements: [
          // Dialog window UI elements.
          {
            id: 'name',
            //type: 'text',
            type: 'select',
            items: arr,
            style: 'width: 400px;',
            label: lang.name,
            'default': '',
            required: true,
            validate: CKEDITOR.dialog.validate.regex(validNameRegex, lang.invalidName),
            setup: function (widget) {
              this.setValue(widget.data.name);
            },
            commit: function (widget) {
              widget.setData('name', this.getValue());
            }
          }
        ]
      }
    ]
  };
});
