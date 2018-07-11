/*!
 * froala_editor v2.8.4 (https://www.froala.com/wysiwyg-editor)
 * License https://froala.com/wysiwyg-editor/terms/
 * Copyright 2014-2018 Froala Labs
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        // Node/CommonJS
        module.exports = function( root, jQuery ) {
            if ( jQuery === undefined ) {
                // require('jQuery') returns a factory that requires window to
                // build a jQuery instance, we normalize how we use modules
                // that require this pattern but the window provided is a noop
                // if it's defined (how jquery works)
                if ( typeof window !== 'undefined' ) {
                    jQuery = require('jquery');
                }
                else {
                    jQuery = require('jquery')(root);
                }
            }
            return factory(jQuery);
        };
    } else {
        // Browser globals
        factory(window.jQuery);
    }
}(function ($) {

  $.extend($.FE.DEFAULTS, {
    aviaryKey: '542e1ff5d5144b9b81cef846574ba6cf',
    aviaryScriptURL: 'https://dme0ih8comzn4.cloudfront.net/imaging/v3/editor.js',
    aviaryOptions: {
      displayImageSize: true,
      theme: 'minimum'
    }
  });

  $.FE.PLUGINS.imageAviary = function (editor) {
    var current_image;

    // Load script in the editor.
    function _loadScript (src, callback) {

      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.defer = 'defer';
      script.src = src;
      script.innerText = '';
      script.onload = callback;

      document.getElementsByTagName('head')[0].appendChild(script);
    }

    function _init () {
      if (!editor.shared.feather_editor) {
        editor.shared.feather_editor = true;

        if (typeof Aviary === 'undefined') {
          _loadScript(editor.opts.aviaryScriptURL, _initAviary);
        }
        else {
          _initAviary();
        }
      }
    }

    function _initAviary() {
      /*global Aviary*/
      editor.shared.feather_editor = new Aviary.Feather($.extend({
        apiKey: editor.opts.aviaryKey,
        onSave: function (image, new_url) {

          // Read image and upload it.
          var img = new Image();
          img.crossOrigin = 'Anonymous';
          img.onload = function () {
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            canvas.height = this.height;
            canvas.width = this.width;
            ctx.drawImage(this, 0, 0);
            var data_URL = canvas.toDataURL('image/png');

            // Convert image to blob.
            var binary = atob(data_URL.split(',')[1]);
            var array = [];

            for (var i = 0; i < binary.length; i++) {
              array.push(binary.charCodeAt(i));
            }
            var upload_img = new Blob([new Uint8Array(array)], {
              type: 'image/png'
            });

            // Select image and upload.
            editor.shared.feather_editor.instance.image.edit($(editor.shared.feather_editor.current_image));
            editor.shared.feather_editor.instance.image.upload([upload_img]);

            // Close editor.
            editor.shared.feather_editor.close();
          };
          img.src = new_url;

          editor.shared.feather_editor.showWaitIndicator();
        },
        onError: function (errorObj) {
          throw new Error(errorObj.message);
        },
        onClose: function () {
          if (!editor.shared.feather_editor.instance.image.get()) {
            editor.shared.feather_editor.instance.image.edit($(editor.shared.feather_editor.current_image));
          }
        }
      }, editor.opts.aviaryOptions));
    }

    function launch (instance) {
      if (typeof instance.shared.feather_editor === 'object') {
        instance.shared.feather_editor.current_image = instance.image.get()[0];
        instance.shared.feather_editor.instance = instance;

        instance.shared.feather_editor.launch({
          image: instance.image.get()[0],
          url: instance.image.get()[0].src
        });
      }
    }

    return {
      _init: _init,
      launch: launch
    }

  };

  $.FE.DefineIcon('aviary', {
    NAME: 'sliders',
    FA5NAME: 'sliders-h'
  });

  $.FE.RegisterCommand('aviary', {
    title: 'Advanced Edit',
    undo: false,
    focus: false,
    callback: function (cmd, val) {
      this.imageAviary.launch(this);
    },
    plugin: 'imageAviary'
  });

  // Look for image plugin.
  if (!$.FE.PLUGINS.image) {
    throw new Error('Image Aviary plugin requires image plugin.');
  }

  $.FE.DEFAULTS.imageEditButtons.push('aviary');

}));
