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

  
  $.FE.DEFAULT_SCAYT_OPTIONS = {
    enableOnTouchDevices: false,
    disableOptionsStorage: ['all'],
    localization:'en',
    extraModules: 'ui',
    DefaultSelection: 'American English',
    spellcheckLang: 'en_US',
    contextMenuSections: 'suggest|moresuggest',
    serviceProtocol: 'https',
    servicePort:'80',
    serviceHost:'svc.webspellchecker.net',
    servicePath:'spellcheck/script/ssrv.cgi',
    contextMenuForMisspelledOnly: true,
    scriptPath: 'https://svc.webspellchecker.net/spellcheck31/wscbundle/wscbundle.js'
  }

  $.extend($.FE.DEFAULTS, {
    scaytAutoload: false,
    scaytCustomerId: '1:tLBmI3-7rr3J1-GMEFA1-mIewo-hynTZ1-PV38I1-uEXCy2-Rn81L-gXuG4-NUNri4-5q9Q34-Jd',
    scaytOptions: {}
  });

  $.FE.PLUGINS.spellChecker = function (editor) {
    var object;

    // Refresh button in toolbar.
    function refresh ($btn) {
      if (object && object.isDisabled) {
        var active = !object.isDisabled();
        $btn.toggleClass('fr-active', active).attr('aria-pressed', active);

        editor.$el.attr('spellcheck', editor.opts.spellcheck && !active);
      }
    }

    // Remove markup from the current selection.
    function _beforeCommand (cmd) {
      if (!object || !object.isDisabled || object.isDisabled()) return;

      if (['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'html'].indexOf(cmd) >= 0) {
        object.removeMarkupInSelectionNode({
          removeInside: true
        });
      }
    }

    // Reload markup on the current selection.
    function _afterCommand (cmd) {
      if (!object || !object.isDisabled || object.isDisabled()) return;

      if (['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'html'].indexOf(cmd) >= 0) {
        object.reloadMarkup();
      }
    }

    // Key press.
    function _keyPress (e) {
      if (!object || !object.isDisabled || object.isDisabled()) return;

      var key_code = e.which;

      // Reload markup on ENTER.
      if (key_code == $.FE.KEYCODE.ENTER) {
        setTimeout(object.reloadMarkup, 0);
      }
    }

    // Toggle spellchecker.
    function toggle () {
      if (!object || !object.isDisabled) return;

      object.setDisabled(!object.isDisabled());
    }

    // Clean HTML on get.
    function _cleanOnGet (el) {
      // Tag is image.
      if (el && el.getAttribute && el.getAttribute('data-scayt-word')) {
        el.outerHTML = el.innerHTML;
      }

      // Look for inner nodes that might be in a similar case.
      else if (el && el.nodeType == Node.ELEMENT_NODE) {
        var els = el.querySelectorAll('[data-scayt-word], [data-spelling-word]');

        for (var i = 0; i < els.length; i++) {
          els[i].outerHTML = els[i].innerHTML;
        }
      }
    }

    function _loaded () {
      // Set events.
      editor.events.on('commands.before', _beforeCommand);
      editor.events.on('commands.after', _afterCommand);
      editor.events.on('keydown', _keyPress, true);

      // Remove markup when getting the HTML.
      editor.events.on('html.processGet', _cleanOnGet);

      // Refresh;
      refresh(editor.$tb.find('[data-cmd="spellChecker"]'));
    }

    function _doInit () {
      // Get SCAYT default options and overide them.
      var scayt_options = editor.opts.scaytOptions;
      scayt_options.customerId = editor.opts.scaytCustomerId;
      scayt_options.container = editor.$iframe ? editor.$iframe.get(0) : editor.$el.get(0);
      scayt_options.autoStartup = editor.opts.scaytAutoload;
      scayt_options.onLoad = _loaded;

      // Set language.
      if (editor.opts.language !== null) {
        editor.opts.spellCheckerLanguage = editor.opts.language;
      }

      // Disable spellcheck if there is scayt.
      if (editor.opts.scaytAutoload === true) {
        editor.opts.spellcheck = false;
      }

      object = new SCAYT.CUSTOMSCAYT(scayt_options);
    }

    // Initialize.
    function _init () {
      if (!editor.$wp) return false;

      editor.opts.scaytOptions = $.extend({}, $.FE.DEFAULT_SCAYT_OPTIONS, editor.opts.scaytOptions);

      if (typeof SCAYT !== 'undefined') {
        _doInit();
      }
      else {
        if (!editor.shared.spellCheckerLoaded) editor.shared.spellCheckerCallbacks = [];
        editor.shared.spellCheckerCallbacks.push(_doInit);

        if (!editor.shared.spellCheckerLoaded) {
          editor.shared.spellCheckerLoaded = true;

          // Init SCAYT.
          var script = document.createElement('script');
          script.type = 'text/javascript';
          script.src = editor.opts.scaytOptions.scriptPath;
          script.innerText = '';
          script.onload = function () {
            /*global SCAYT */
            for (var i = 0; i < editor.shared.spellCheckerCallbacks.length; i++) {
              editor.shared.spellCheckerCallbacks[i]();
            }
          }

          document.getElementsByTagName('head')[0].appendChild(script);
        }
      }
    }

    return {
      _init: _init,
      refresh: refresh,
      toggle: toggle
    }
  };

  // Register spellchecker command.
  $.FE.DefineIcon('spellChecker', {
    NAME: 'keyboard-o',
    FA5NAME: 'keyboard'
  });
  $.FE.RegisterCommand('spellChecker', {
    title: 'Spell Checker',
    undo: false,
    focus: false,
    accessibilityFocus: true,
    forcedRefresh: true,
    toggle: true,
    callback: function () {
      this.spellChecker.toggle();
    },
    refresh: function ($btn) {
      this.spellChecker.refresh($btn);
    },
    plugin: 'spellChecker'
  });

}));
