function BxStripeConnectMain(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oStripeConnectMain' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxStripeConnectMain.prototype.accountCreate = function(iId, oLink) {
    var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.get(
        this._sActionsUrl + 'account_create/',
        {
            id: iId,
            _t: oDate.getTime()
        },
        function(oData) {
            if(parseInt(oData.code) != 0)
                $this.loadingInButton(oLink, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxStripeConnectMain.prototype.accountContinue = function(iId, oLink) {
    var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.get(
        this._sActionsUrl + 'account_continue/',
        {
            id: iId,
            _t: oDate.getTime()
        },
        function(oData) {
            if(parseInt(oData.code) != 0)
                $this.loadingInButton(oLink, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxStripeConnectMain.prototype.accountDelete = function(iId, oLink) {
    var $this = this;
    var oDate = new Date();

    this.loadingInButton(oLink, true);

    $.get(
        this._sActionsUrl + 'account_delete/',
        {
            id: iId,
            _t: oDate.getTime()
        },
        function(oData) {
            if(parseInt(oData.code) != 0)
                $this.loadingInButton(oLink, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxStripeConnectMain.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxStripeConnectMain.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};
