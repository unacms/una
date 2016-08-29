function BxMarketApiKands(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxMarketApiKands' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxMarketApiKands.prototype.paOnShowPopup = function(oSelect) {
	var $this = this;

	$('#' + this._aHtmlIds['field_user']).autocomplete({
        source: this._sActionsUrl + 'get_users/',
        select: function(oEvent, oUi) {
        	$('#' + $this._aHtmlIds['field_user_id']).val(oUi.item.value);
            $(this).val(oUi.item.label);

            oEvent.preventDefault();
        }
    });
};