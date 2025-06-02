function BxStripeConnectEmbeds(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oStripeConnectEmbeds' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    this._sPublicKey = oOptions.sPublicKey;
    this._iProfileId = oOptions.iProfileId;

    this._oStripeConnect = null;

    this.init();
}

BxStripeConnectEmbeds.prototype.init = function() {
    var $this = this;

    window.StripeConnect = window.StripeConnect || {};

    StripeConnect.onLoad = () => {
        $this._oStripeConnect = StripeConnect.init({
            publishableKey: $this._sPublicKey,
            fetchClientSecret: function() {
                var sSecret = '';

                $.ajax({
                    url: $this._sActionsUrl + 'account_session_create/' + $this._iProfileId,
                    async: false, 
                    dataType: 'json',
                    success: function (oData) {
                        if(parseInt(oData.code) != 0)
                            return;

                        sSecret = oData.secret
                    }
                });

                return sSecret;
            }
        });
      };
};

BxStripeConnectEmbeds.prototype.load = function(sEmbed) {
    var $this = this;
    var oContainer = $('#' + this._aHtmlIds[sEmbed]);

    this.loadingInContainer(oContainer, true);

    var fCreate = function() {
        if(!$this._oStripeConnect) {
            setTimeout(fCreate, 1000);
            return;
        }

        oContainer.html($this._oStripeConnect.create(sEmbed));
    };

    $(document).ready(fCreate);
};

BxStripeConnectEmbeds.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);
};

BxStripeConnectEmbeds.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxStripeConnectEmbeds.prototype.loadingInContainer = function(e, bShow) {
    var oContainer = $(e).length ? $(e) : $('body'); 
    bx_loading(oContainer, bShow);
};
