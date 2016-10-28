function BxPaymentMain() {}

BxPaymentMain.prototype.processResult = function(oData) {
	var $this = this;

	if(oData && oData.message != undefined && oData.message.length != 0)
    	alert(oData.message);

    if(oData && oData.reload != undefined && parseInt(oData.reload) == 1)
    	document.location = document.location;

    if(oData && oData.popup != undefined) {
    	var oPopup = null;
    	var oOptions = {
            fog: {
				color: '#fff',
				opacity: .7
            },
            closeOnOuterClick: false
        };

    	if(typeof(oData.popup) == 'object') {
    		oOptions = $.extend({}, oOptions, oData.popup.options);
    		oPopup = $(oData.popup.html);
    	}
    	else 
    		oPopup = $(oData.popup);

    	$('#' + oPopup.attr('id')).remove();
        oPopup.hide().prependTo('body').bxTime().dolPopup(oOptions);
    }

    if (oData && oData.eval != undefined)
        eval(oData.eval);
};

BxPaymentMain.prototype.loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxPaymentMain.prototype.loadingInPopup = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-popup-content:first') : $('body'); 
	bx_loading(oParent, bShow);
};