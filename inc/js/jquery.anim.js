/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

$.fn.bx_anim = function(action, effect, speed, h) {
   return this.each(function() {
           var sFunc = '';
           var sEval;

           if (speed == 0)
               effect = 'default';

          switch (action) {
              case 'show':
                  switch (effect) {
                      case 'slide': sFunc = 'slideDown'; break;
                      case 'fade': sFunc = 'fadeIn'; break;
                      default: sFunc = 'show';
                  }
                  break;
              case 'hide':
                  switch (effect) {
                      case 'slide': sFunc = 'slideUp'; break;
                      case 'fade': sFunc = 'fadeOut'; break;
                      default: sFunc = 'hide';
                  }
                  break;
              default:
              case 'toggle':
                  switch (effect) {
                      case 'slide': sFunc = 'slideToggle'; break;
                      case 'fade': sFunc = ($(this).filter(':visible').length) ? 'fadeOut' : 'fadeIn'; break;
                      default: sFunc = 'toggle';
                  }
          }

          if ((0 == speed || undefined == speed) && undefined == h) {
              sEval = '$(this).' + sFunc + '();';
          }
          else if ((0 == speed || undefined == speed) && undefined != h) {
              sEval = '$(this).' + sFunc + '(); $(this).each(h);';
          }
          else {
              sEval = '$(this).' + sFunc + "('" + speed + "', h);";
          }
          eval(sEval);

          return this;
   });
};

$.fn.bx_loading = function(bForceIndicator) {
    return $.fn._bx_loading('.bx-loading-ajax', bForceIndicator);
};

$.fn.bx_loading_btn = function(bForceIndicator) {
    return $.fn._bx_loading('.bx-loading-ajax-btn', bForceIndicator);
};

$.fn._bx_loading = function(sLoadingSelector, bForceIndicator) {
    return this.each(function() {
        var oContainer = $(this);
        var oLoading = oContainer.find(sLoadingSelector);
        bForceIndicator = 'undefined' == typeof(bForceIndicator) ? oLoading.length && oLoading.is(':visible') : bForceIndicator;
        bx_loading_btn(oContainer, bForceIndicator);
    });
};

$.fn.bx_message_box = function(sMessage, iTimer, onClose) {
    return this.each(function() {
        var oParent = $(this);
        
        if(oParent.children(':first').hasClass('MsgBox'))
            oParent.children(':first').replaceWith(sMessage);
        else 
            oParent.prepend(sMessage);

        if(iTimer == undefined || parseInt(iTimer) == 0)
            return;

        setTimeout(function(oParent, onClose) {
            oParent.children('div.MsgBox:first').bx_anim('hide', 'fade', 'slow', function(){
                $(this).remove();
                if(onClose != undefined)
                    onClose();
            });
        }, 1000 * parseInt(iTimer), oParent, onClose);
    });
};

/** @} */
