/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

/*
 * Functions for "Form View Image Uploader" fields.
 */
function fviuDelete(sName, oLink) {
	var $this = this;
	var oDate = new Date();

	/*
	$.post(
		this.sActionsUrl,
		{
			dsg_action: 'delete_logo',
			_t: oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0 && oData.message.length > 0) {
				bx_alert(oData.message);
				return;
			}

			document.location.href = document.location.href; 
		},
		'json'
	);
	*/
}

/*
 * Functions for "Form View Translatable" fields. 
 */
function fvtTogglePopup(sName, oLink) {
    var oPopup = $(oLink).parents('.bx-form-input-translator:first').find('#bx-form-field-translator-popup-' + sName);
    if(oPopup.filter(':visible').length > 0) {
        oPopup.dolPopupHide();
        return;
    }

    oPopup.dolPopup({
    	moveToDocRoot: false,
        pointer:{
            el:$(oLink)
        }
    });
};

function fvtSelectLanguage(sName, sLangName, oLink) {
    var oTranslator = $(oLink).parents('.bx-form-input-translator:first');

    //--- Update popup languages
    var sId = '#bx-form-language-' + sName + '-' + sLangName;
    $(oLink).parent().siblings('.active:visible').hide().siblings('.not-active:hidden').show().siblings(sId + '-pas:visible').hide().siblings(sId + '-act:hidden').show();

    //--- Update current language
    oTranslator.find('.bx-form-input-language-current').css('background-image', $(oLink).parent('.bx-form-input-language').css('background-image')).find('a').html($(oLink).html());

    //--- Close selector
    oTranslator.find('#bx-form-field-translator-popup-' + sName).dolPopupHide();

    //--- Update inputs
    $(oLink).parents('#bx-form-element-' + sName + ':first').find('.bx-form-input-translation:visible').hide().siblings('.bx-form-input-translation-' + sName + '-' + sLangName + ':hidden').show();
};

/** @} */
