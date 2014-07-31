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

BxDolStudioDashboard.prototype.getBlockContent = function(sType) {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-' + sType;

	bx_loading('bx-dbd-' + sType, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'get_block',
			dbd_value: sType,
			_t: oDate.getTime()
		},
		function(oData) {
			bx_loading('bx-dbd-' + sType, false);

			if(!oData.data)
			    return;

			$('#' + sDivId).replaceWith(oData.data);
		},
		'json'
	);
};

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

	bx_loading('bx-dbd-' + sType, true);
	google.load("visualization", "1", {packages:["corechart"], callback: function() {
    	bx_loading('bx-dbd-' + sType, false);
    	$this.showChart(sType, oData);
    }});
};

BxDolStudioDashboard.prototype.showChart = function(sType, oData) {
	var oChart = $('#bx-dbd-' + sType + ' .bx-dbd-chart');
	oChart.html('');

    var oDataTable = new google.visualization.DataTable();
    oDataTable.addColumn('string', 'Label');
    oDataTable.addColumn('number', 'Size');   

    if(oData != undefined)
		this.oData = oData;
    oDataTable.addRows(this.oData);

    var oChart = new google.visualization.PieChart(oChart[0]);
    oChart.draw(oDataTable, {
		chartArea: {
			left:10,
			top:10,
			width:'92%',
			height:'92%'
		}
    });
};

BxDolStudioDashboard.prototype.clearCache = function(sType) {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-cache';

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

        	if(oData.message != undefined && oData.message.length > 0)
    			$this.popup(oData.message);

            if(oData.data != undefined) {
            	if(typeof oData.data == 'object')
            		$this.showChart('cache', oData.data);
            	else if(typeof oData.data == 'string')
            		$('#' + sDivId).html(oData.data);
            }
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