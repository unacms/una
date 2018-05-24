/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
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
    	$('.bx-dbd-block-content').bxProcessHtml();
    });
}

BxDolStudioDashboard.prototype.checkForUpgrade = function() {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-version';

	bx_loading(sDivId, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'check_for_upgrade',
			_t: oDate.getTime()
		},
		function(oData) {
			bx_loading(sDivId, false);

			if(!oData.data)
			    return;

			$('#' + sDivId + ' .bx-dbd-version-available').html(oData.data).show();
		},
		'json'
	);
};

BxDolStudioDashboard.prototype.performUpgrade = function() {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-version';

	bx_loading(sDivId, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'perform_upgrade',
			_t: oDate.getTime()
		},
		function(oData) {
			bx_loading(sDivId, false);

			if(!oData.message)
			    return;

			$this.popup(oData.message);
		},
		'json'
	);
};

BxDolStudioDashboard.prototype.getBlockContent = function(sType) {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-' + sType;

	bx_loading(sDivId, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'get_block',
			dbd_value: sType,
			_t: oDate.getTime()
		},
		function(oData) {
			bx_loading(sDivId, false);

			if(!oData.data)
			    return;

			$('#' + sDivId).replaceWith(oData.data);
		},
		'json'
	);
};

BxDolStudioDashboard.prototype.initChart = function(sType, oData) {
	var $this = this;
	var sDivId = 'bx-dbd-' + sType;

	bx_loading(sDivId, true);
	google.load("visualization", "1", {packages:["corechart"], callback: function() {
    	bx_loading(sDivId, false);
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

BxDolStudioDashboard.prototype.permissions = function() {
    this.hostTools('permissions');
};

BxDolStudioDashboard.prototype.serverAudit = function() {
    this.hostTools('server_audit');
};

BxDolStudioDashboard.prototype.hostTools = function(sAction) {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-htools';

	$('#' + sDivId).parents('.bx-page-block-container').find('.bx-db-header .bx-popup-applied:visible').dolPopupHide();

	bx_loading(sDivId, true);

	$.get(
		this.sActionsUrl,
		{
			dbd_action: sAction,
			_t: oDate.getTime()
		},
		function(sData) {
			bx_loading(sDivId, false);

			if(!sData.length)
			    return;

			$('#' + sDivId).hide().html(sData).bx_anim('show', 'fade', 'slow', function() {
				$(this).bxProcessHtml();
			});
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
