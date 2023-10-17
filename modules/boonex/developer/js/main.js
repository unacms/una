function BxDevMain(oOptions) {
    this._sActionUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDevMain' : oOptions.sObjName;

    this._sTxtExportType = oOptions.sTxtExportType == undefined ? '' : oOptions.sTxtExportType;
    this._sTxtBasic = oOptions.sTxtBasic == undefined ? '' : oOptions.sTxtBasic;
    this._sTxtFull = oOptions.sTxtFull == undefined ? '' : oOptions.sTxtFull;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDevMain.prototype.import = function(oButton, sType) {
    this.performAction(oButton, 'import', {type: sType});
};

BxDevMain.prototype.onImport = function(oData) {
    document.location.href = document.location.href;
};

BxDevMain.prototype.export = function(oButton, sType) {
    this.performAction(oButton, 'export', {type: sType});
};

BxDevMain.prototype.onExport = function(oData) {
    bx_confirm(this._sTxtExportType, function() {
        document.location.href = bx_append_url_params(oData.url, {full: 0});
    }, function() {
        document.location.href = bx_append_url_params(oData.url, {full: 1});
    }, {
        yes: {
            title: this._sTxtBasic
        },
        no: {
            title: this._sTxtFull
        }
    });
};

BxDevMain.prototype.performAction = function(oSource, sAction, aParams, bConfirm) {
    var $this = this;
    var oDate = new Date();

    bx_loading(oSource, true);

    var oPerform = function() {
        $.post(
            $this._sActionUrl + sAction,
            $.extend({}, aParams, {_t: oDate.getTime()}),
            function (oData) {
                bx_loading(oSource, false);

                processJsonData(oData);
            },
            'json'
        );
    };

    if(bConfirm != undefined && parseInt(bConfirm) == 1)
	bx_confirm('', oPerform);
    else
        oPerform();
};
