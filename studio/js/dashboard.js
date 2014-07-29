/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
function BxDolStudioDashboard(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioDashboard' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this.oData = [];

    var $this = this;
    $(document).ready(function() {
    	$('.bx-dbd-block-content').bxTime();

    	$this.checkForUpdateScript();
    });
}

BxDolStudioDashboard.prototype.checkForUpdateScript = function() {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-update-script';

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'check_update_script',
			_t: oDate.getTime()
		},
		function(oData) {
			if(!oData.version)
			    return;

			$('#' + sDivId + ' span').html(_t('_adm_dbd_txt_dolphin_n_available', oData.version)).parents('#' + sDivId + ':hidden').show();
		},
		'json'
	);
};

BxDolStudioDashboard.prototype.initChart = function(sType, oData) {
	var $this = this;

    google.setOnLoadCallback(function() {
    	$this.showChart(sType, oData);
    });
};

BxDolStudioDashboard.prototype.showChart = function(sType, oData) {
	var sDivId = 'bx-dbd-' + sType + '-chart';
    $('#' + sDivId).html('');

    var oDataTable = new google.visualization.DataTable();
    oDataTable.addColumn('string', 'Label');
    oDataTable.addColumn('number', 'Size');   

    if(oData != undefined)
		this.oData = oData;
    oDataTable.addRows(this.oData);

    var oOptions = {
        	width: 512,
        	height: 256,

			chartArea: {
				left:10,
				top:10,
				width:'92%',
				height:'92%'
			}
        };

    var oChart = new google.visualization.PieChart($('#' + sDivId)[0]);
    oChart.draw(oDataTable, oOptions);
};

BxDolStudioDashboard.prototype.clearCache = function(sType) {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-cache-chart';

	$('#' + sDivId).parents('.bx-page-block-container').find('.bx-db-header .bx-popup-applied:visible').dolPopupHide();

	bx_loading(sDivId, true);

    $.post(
    	sUrlStudio + 'dashboard.php', 
        {
    		dbd_action: 'clear_cache',
    		dbd_value: sType,
            _t: oDate.getTime()
        }, 
        function(oData) {
        	bx_loading(sDivId, false);

        	if(oData.message.length > 0)
    			$this.popup(oData.message);

            if(oData.data != undefined)
            	$this.showChart('cache', oData.data);
        },
        'json'
    );
};

BxDolStudioDashboard.prototype.serverAudit = function() {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-host-tools';

	$('#' + sDivId).parents('.bx-page-block-container').find('.bx-db-header .bx-popup-applied:visible').dolPopupHide();

	bx_loading(sDivId, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'server_audit',
			_t: oDate.getTime()
		},
		function(sData) {
			bx_loading(sDivId, false);

			if(!sData.length)
			    return;

			$('#' + sDivId).hide().html(sData).bx_anim('show', 'fade', 'slow');
		},
		'html'
	);
};

BxDolStudioDashboard.prototype.popup = function(sValue) {
	var sId = 'bx-std-dbd-popup';

    $('#' + sId).remove();
    $('<div id="' + sId + '" style="display: none;"></div>').prependTo('body').html(sValue);
    $('#' + sId).dolPopup({});
};
/** @} */