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

  

  // Extend defaults.
  $.extend($.FE.DEFAULTS, {
    saveInterval: 10000,
    saveURL: null,
    saveParams: {},
    saveParam: 'body',
    saveMethod: 'POST'
  });


  $.FE.PLUGINS.save = function (editor) {
    var _timeout = null;
    var _last_html = null;
    var _force = false;

    var BAD_LINK = 1;
    var ERROR_ON_SERVER = 2;

    var error_messages = {};
    error_messages[BAD_LINK] = 'Missing saveURL option.';
    error_messages[ERROR_ON_SERVER] = 'Something went wrong during save.';

    /**
     * Throw an image error.
     */
    function _throwError (code, response) {
      editor.events.trigger('save.error', [{
        code: code,
        message: error_messages[code]
      }, response]);
    }

    function save (html) {
      if (typeof html == 'undefined') html = editor.html.get();

      var original_html = html;

      // Trigger before save event.
      var event_returned_value = editor.events.trigger('save.before', [html]);

      if (event_returned_value === false) return false;
      else if (typeof event_returned_value == 'string') html = event_returned_value;

      if (editor.opts.saveURL) {
        var params = {};

        for (var key in editor.opts.saveParams) {
          if (editor.opts.saveParams.hasOwnProperty(key)) {
            var param = editor.opts.saveParams[key];

            if (typeof(param) == 'function') {
              params[key] = param.call(this);
            }
            else {
              params[key] = param;
            }
          }
        }

        var dt = {};
        dt[editor.opts.saveParam] = html;

        $.ajax({
          type: editor.opts.saveMethod,
          url: editor.opts.saveURL,
          data: $.extend(dt, params),
          crossDomain: editor.opts.requestWithCORS,
          xhrFields: {
            withCredentials: editor.opts.requestWithCredentials
          },
          headers: editor.opts.requestHeaders
        })
        .done(function (data) {
          _last_html = original_html;

          // data
          editor.events.trigger('save.after', [data]);
        })
        .fail(function (xhr) {

          // (error)
          _throwError(ERROR_ON_SERVER, xhr.response || xhr.responseText);
        });
      }
      else {

        // (error)
        _throwError(BAD_LINK);
      }
    }

    function _mightSave () {
      clearTimeout(_timeout);
      _timeout = setTimeout(function () {
        var html = editor.html.get();

        if (_last_html != html || _force) {
          _last_html = html;
          _force = false;

          save(html);
        }
      }, editor.opts.saveInterval);
    }

    /**
     * Reset the saving interval.
     */
    function reset () {
      _mightSave();
      _force = false;
    }

    /**
     * Force saving at the end of the current interval.
     */
    function force () {
      _force = true;
    }

    /*
     * Initialize.
     */
    function _init () {
      if (editor.opts.saveInterval) {
        _last_html = editor.html.get();
        editor.events.on('contentChanged', _mightSave);
        editor.events.on('keydown destroy', function () {
          clearTimeout(_timeout);
        });
      }
    }

    return {
      _init: _init,
      save: save,
      reset: reset,
      force: force
    }
  }

  $.FE.DefineIcon('save', {
    NAME: 'floppy-o',
    FA5NAME: 'disk'
  });
  $.FE.RegisterCommand('save', {
    title: 'Save',
    undo: false,
    focus: false,
    refreshAfterCallback: false,
    callback: function () {
      this.save.save();
    },
    plugin: 'save'
  });

}));
