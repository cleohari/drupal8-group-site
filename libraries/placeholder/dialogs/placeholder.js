
/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

/**
 * @fileOverview Definition for placeholder plugin dialog.
 *
 */

'use strict';

CKEDITOR.dialog.add( 'placeholder', function( editor ) {
	var lang = editor.lang.placeholder,
		generalLabel = editor.lang.common.generalTab,
		validNameRegex = /^[^\[\]<>]+$/;

	//var temparray = [ ['Company'], ['Email'], ['First Name'], ['Last Name'],['This guy'] ];
  //var temparray = [ 'Company', 'Email', 'First Name', 'Last Name','This guy' ];
	var myString = document.getElementById('available_tokens').innerText;
	var temparray = myString.split(",");

  var arr = new Array();
  //arr[0] = [];
  for (var i = 0; i < temparray.length; i++) {
    alert(temparray[i]);
    arr[i] = new Array();
    arr[i][0] = temparray[i];
    //Do something
  }

	/*var arr = [];
	arr[0] = [];
	arr[0][0] = temparray[0];
  arr[0][1] = temparray[1];
  arr[0][2] = temparray[2];*/
	/*for(var i=0;i<3;++i){
    arr[0][i] = temparray[i];
		//alert(temparray[i]);
	}*/
	//alert(arr);
	//arr[0] = temparray;
	// for(var i=0; i< 1;i++)
	// {
	// 	var columns=[];
	// 	for(var j=0; j<3; j++)
	// 	{
	//
	// 	}
	// 	arr[i]
	// }
	alert(arr);
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
						validate: CKEDITOR.dialog.validate.regex( validNameRegex, lang.invalidName ),
						setup: function( widget ) {
							this.setValue( widget.data.name );
						},
						commit: function( widget ) {
							widget.setData( 'name', this.getValue() );
						}
					}
				]
			}
		]
	};
} );
