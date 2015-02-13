/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
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
				alert(oData.message);
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
	var oPopup = $('#bx-form-field-translator-popup-' + sName);
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
	//--- Update popup languages
	var sId = '#bx-form-language-' + sName + '-' + sLangName;
    $(oLink).parent().siblings('.active:visible').hide().siblings('.not-active:hidden').show().siblings(sId + '-pas:visible').hide().siblings(sId + '-act:hidden').show();

    //--- Update current language
    var sIdElement = '#bx-form-element-' + sName;
    $(sIdElement).find('.bx-form-input-language-current').css('background-image', $(oLink).parent('.bx-form-input-language').css('background-image')).find('a').html($(oLink).html());

    //--- Update inputs
    var sIdInput = '#bx-form-input-' + sName + '-' + sLangName;
    $(sIdElement).find('.bx-form-input-text:visible, .bx-form-input-textarea:visible').hide().siblings(sIdInput + ':hidden').show();

    //--- Close selector
    $('#bx-form-field-translator-popup-' + sName).dolPopupHide();
};

/** @} */
