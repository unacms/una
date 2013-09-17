function BxDevMain(oOptions) {
    this._sSystem = oOptions.sSystem;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oDevMain' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDevMain.prototype.pgtRecompile = function(sLanguage) {
    var $this = this;
    var oDate = new Date();
    var oParams = {
        _t:oDate.getTime()
    };

    if(sTypeParams)
        oParams['params'] = sTypeParams;

    if($('#articles-filter-chb:checked').length > 0 && $('#articles-filter-txt').val().length > 0)
        oParams['filter_value'] = $('#articles-filter-txt').val();

    var sLoadingId = '#articles-' + sType + '-loading';
    $(sLoadingId).bx_loading();

    $.post(
        this._sActionsUrl + 'act_get_articles/' + (sType ? sType + '/' : '') + iStart + '/' + iPerPage + '/',
        oParams,
        function(sData) {
            $(sLoadingId).bx_loading();

            $('.arl-view #arl-content-' + sType).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).replaceWith(sData);
            });
        },
        'html'
    );
};