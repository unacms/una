/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioSettings(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioSettings' : oOptions.sObjName;

    this.sType = oOptions.sType == undefined ? '' : oOptions.sType;
    this.sCategory = oOptions.sCategory == undefined ? '' : oOptions.sCategory;
    this.sMix = oOptions.sMix == undefined ? '' : oOptions.sMix;

    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioSettings.prototype.mixCreate = function(oButton) {
    this.mixActionWithValue(oButton, 'create-mix');
};
BxDolStudioSettings.prototype.onMixCreate = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixImport = function(oButton) {
    this.mixActionWithValue(oButton, 'import-mix');
};
BxDolStudioSettings.prototype.onMixImport = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixSelect = function(oSelect) {
    this.mixActionWithValue(oSelect, 'select-mix', $(oSelect).val());
};
BxDolStudioSettings.prototype.onMixSelect = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixExport = function(oButton, iId) {
    this.mixActionWithValue(oButton, 'export-mix', iId);
};
BxDolStudioSettings.prototype.onMixExport = function(oData) {
    document.location.href = oData.url;
};

BxDolStudioSettings.prototype.mixPublish = function(oButton, iId) {
    this.mixActionWithValue(oButton, 'publish-mix', iId);
};
BxDolStudioSettings.prototype.onMixPublish = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixHide = function(oButton, iId) {
    this.mixActionWithValue(oButton, 'hide-mix', iId);
};
BxDolStudioSettings.prototype.onMixHide = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixDelete = function(oButton, iId) {
    this.mixActionWithValue(oButton, 'delete-mix', iId, 1);
};
BxDolStudioSettings.prototype.onMixDelete = function(oData) {
    document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixAction = function(oSource, sAction) {
    var $this = this;
    var oDate = new Date();

    $.post(
        sUrlStudio + 'settings.php',
        {
            page: this.sType,
            category: this.sCategory,
            stg_action: sAction,
            _t:oDate.getTime()
        },
        function (oData) {
            $this.processResult(oData);
        },
        'json'
    );
};

BxDolStudioSettings.prototype.mixActionWithValue = function(oSource, sAction, mixedValue, bConfirm) {
    var $this = this;
    var oDate = new Date();

    var oPerform = function() {
        $.post(
            sUrlStudio + 'settings.php',
            {
                page: $this.sType,
                category: $this.sCategory,
                stg_action: sAction,
                stg_value: mixedValue,
                _t:oDate.getTime()
            },
            function (oData) {
                $this.processResult(oData);
            },
            'json'
        );
    };

    if(bConfirm != undefined && parseInt(bConfirm) == 1)
	bx_confirm('', oPerform);
    else
        oPerform();
};

/**
 * Is needed if AJAX is used to change (reload) pages. 
 */
BxDolStudioSettings.prototype.changePage = function(sType) {
    var oDate = new Date();
    var $this = this;

    $.get(
        this.sActionsUrl,
        {
            stg_action: 'get-page-by-type',
            stg_value: sType,
            _t:oDate.getTime()
        },
        function(oData) {
            if(oData.code != 0) {
                bx_alert(oData.message);
                return;
            }

            $('#bx-std-pc-menu > .bx-std-pmi-active').removeClass('bx-std-pmi-active');
            $('#bx-std-pmi-' + sType).addClass('bx-std-pmi-active');

            $('#bx-std-pc-content').bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
                $(this).html(oData.content).bx_anim('show', $this.sAnimationEffect, $this.iAnimationSpeed);
            });
        },
        'json'
    );

    return true;
};

BxDolStudioSettings.prototype.processResult = function(oData) {
    var $this = this;

    if(oData && oData.message != undefined && oData.message.length != 0)
        $(document).dolPopupAlert({
            message: oData.message
        });

    if(oData && oData.reload != undefined && parseInt(oData.reload) == 1)
    	document.location = document.location;

    if(oData && oData.popup != undefined) {
    	var oPopup = $(oData.popup).hide(); 

    	$('#' + oPopup.attr('id')).remove();
        oPopup.prependTo('body').dolPopup({
            fog: {
                color: '#fff',
                opacity: .7
            },
            closeOnOuterClick: false
        });
    }

    if (oData && oData.eval != undefined)
        eval(oData.eval);
};
/** @} */
