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
    inlineStyles: {
      'Big Red': 'font-size: 20px; color: red;',
      'Small Blue': 'font-size: 14px; color: blue;'
    }
  })

  $.FE.PLUGINS.inlineStyle = function (editor) {
    function apply (val) {
      if (editor.selection.text() !== '') {
        var splits = val.split(';');

        for (var i = 0; i < splits.length; i++) {
          var new_split = splits[i].split(':');

          if (splits[i].length && new_split.length == 2) {
            editor.format.applyStyle(new_split[0].trim(), new_split[1].trim())
          }
        }
      }
      else {
        editor.html.insert('<span style="' + val + '">' + $.FE.INVISIBLE_SPACE + $.FE.MARKERS + '</span>');
      }
    }

    return {
      apply: apply
    }
  }

  // Register the inline style command.
  $.FE.RegisterCommand('inlineStyle', {
    type: 'dropdown',
    html: function () {
      var c = '<ul class="fr-dropdown-list" role="presentation">';
      var options =  this.opts.inlineStyles;

      for (var val in options) {
        if (options.hasOwnProperty(val)) {
          c += '<li role="presentation"><span style="' + options[val] + '" role="presentation"><a class="fr-command" tabIndex="-1" role="option" data-cmd="inlineStyle" data-param1="' + options[val] + '" title="' + this.language.translate(val) + '">' + this.language.translate(val) + '</a></span></li>';
        }
      }
      c += '</ul>';

      return c;
    },
    title: 'Inline Style',
    callback: function (cmd, val) {
      this.inlineStyle.apply(val);
    },
    plugin: 'inlineStyle'
  })

  // Add the font size icon.
  $.FE.DefineIcon('inlineStyle', {
    NAME: 'paint-brush'
  });

}));
