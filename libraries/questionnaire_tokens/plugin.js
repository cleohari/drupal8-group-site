/**
 * @fileOverview The "questionnaire_tokens" plugin.
 *
 */

(function () {
  CKEDITOR.plugins.add('questionnaire_tokens', {
    requires: ['richcombo'],
    init: function (editor) {
      console.log('In CKEDITOR.plugins init()');
      var config = editor.config;

      // Gets the list of tags from the settings.
      var tags = []; //new Array();
      //this.add('value', 'drop_text', 'drop_label');
      tags[0] = ["[contact_name]", "Name", "Name"];
      tags[1] = ["[contact_email]", "email", "email"];
      tags[2] = ["[contact_user_name]", "User name", "User name"];

      editor.ui.addRichCombo('questionnaire_tokens',
      {
        label : "Insert tokens",
        title :"Insert tokens",
        voiceLabel : "Insert tokens",
        className : 'cke_format',
        multiSelect : false,

        panel :
        {
          css : [ config.contentsCss, CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ]
          //voiceLabel : lang.panelVoiceLabel
        },

        init : function () {
          console.log('In addRichCombo init()');
          this.startGroup("Questionnaire Tokens");
        }
      });

      //editor.add.addRichCombo('questionnaire_tokens',
      //{
      // label: "Insert tokens",
      // title: "Insert tokens",
      // voiceLabel: "Insert tokens",
      // className: 'cke_format',
      // multiSelect: false,
      // init: function () {
      //   alert('in RichCombo init');
      // }
      //});
    }
    /*init: function (editor) {
      alert('hello!!');
      var config = editor.config;
      editor.ui.addRichCombo('Zoom',
      {
        label: "Dropdown", //label displayed in toolbar
        title: 'Zoom',//popup text when hovering over the dropdown
        multiSelect: false,

        //use the same style as the font/style dropdowns
        panel:
            {
              css: [CKEDITOR.skin.getPath('editor')].concat(config.contentsCss),
            },
        init: function () {
          alert('in addRichCombo init');
          //start group in the dropdown
          this.startGroup('Group 1');
          //VALUE - The value we get when the row is clicked
          //HTML - html/plain text that should be displayed in the dropdown
          //TEXT - displayed in popup when hovered over the row.
          //this.add( VALUE, HTML, TEXT );
          //add row to the first group
          this.add(2, "<h1>Test</h1>", "333");

          //start another group in the dropdown
          this.startGroup('Group 2');
          //add row to the second group.
          this.add("444", "No HTML Here", "666");

          //we can also set the initial value that the dropdown takes
          //when it is clicked for the first time.
          // Default value on first click
          // this.setValue("444", "No HTML Here");
        },
        //this function is called when a row is clicked
        onClick: function (value) {
          //we can check the value to see which row was clicked and respond
          // accordly
        },
      });
      // End of richCombo element
    }*/
  });
})();