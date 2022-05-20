/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolChartGrowth (oOptions) {
    this._sObjName = undefined == oOptions.sObjName ? 'oBxDolChartGrowth' : oOptions.sObjName;    // javascript object name, to run current object instance from onTimer
    this._sActionsUrl = oOptions.sRootUrl + 'chart.php'; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';

    this._sKeyObjects = 'bx_chart_growth_objects';
    this._sKeyDateFrom = 'bx_chart_growth_date_from';
    this._sKeyDateTo = 'bx_chart_growth_date_to';
    this._sKeyGraph = 'bx_chart_growth_graph';

    this._sDateFormat = 'yy-mm-dd';
    this._oChart = null;
    this._oChartOptionsDefault = {};
    this._sChartColorBorder = undefined == oOptions.sChartColorBorder ? 'rgba(74, 144, 226, 1.0)' : oOptions.sChartColorBorder;
    this._sChartColorBackground = undefined == oOptions.sChartColorBackground ? 'rgba(74, 144, 226, 0.4)' : oOptions.sChartColorBackground;

    var $this = this;
    $(document).ready(function() {
    	$this.loadData();
    });
}

BxDolChartGrowth.prototype.loadData = function()
{
	var $this = this;
	var oGraph = $('#' + this._sKeyGraph);
	var oGraphWrp = oGraph.parents('div:first');
	var oGraphErr = oGraphWrp.find('.bx-chart-growth-graph-error').hide();

    $('#' + this._sKeyObjects).attr('disabled', true);

    bx_loading(oGraphWrp, true);

    $.get(
    	this._sActionsUrl,
    	{
    		object: $('#' + this._sKeyObjects).val(),
    		action: 'load_data_by_interval',
    		from: $('#' + $this._sKeyDateFrom).val(),
            to: $('#' + $this._sKeyDateTo).val()
    	},
    	function(oData) {
    		$('#' + $this._sKeyObjects).attr('disabled', false);

            bx_loading(oGraphWrp, false);

            if(oData.error != undefined) {
            	if($this._oChart)
                	$this._oChart.destroy();

            	oGraphErr.html(oData.error).show();
                return;
            } 

            // hide date selector if chart doesn't support date range
            if (oData.hide_date_range) {
                $('#' + $this._sKeyDateFrom).parents('.bx-form-element-wrapper:first').fadeOut();
                $('#' + $this._sKeyDateTo).parents('.bx-form-element-wrapper:first').fadeOut();
            }
            else {
            	$('#' + $this._sKeyDateFrom).parents('.bx-form-element-wrapper:first').fadeIn();
            	$('#' + $this._sKeyDateTo).parents('.bx-form-element-wrapper:first').fadeIn();
            }

            var oChartOptions = oData.options || {};
            var oChartLabels = new Array();
            var oChartData = new Array();

            // create chart arrays
            if(oData.column_date !== false && oData.column_count !== false)
                for (var i in oData.data) {
                    var sDate = oData.data[i][oData.column_date];
                    var aDate = sDate.match(/(\d{4})-(\d{2})-(\d{2})/);
                    if(!aDate || !aDate[1] || !aDate[2] || !aDate[3])
                        continue;

                    oChartLabels[i] = sDate;
                    oChartData[i] = oData.data[i][oData.column_count];
                }

            if($this._oChart)
            	$this._oChart.destroy();

	    	$this._oChart = new Chart(oGraph, {
	    		type: oData.type != undefined ? oData.type : 'line',
	    		data: {
	            	labels: oChartLabels,
	            	datasets: [{
	            		label: oData.title,
	            		data: oChartData,
	            		borderColor: $this._sChartColorBorder,
	            		backgroundColor: $this._sChartColorBackground
	            	}]
	            },
	    		options: $.extend({}, $this._oChartOptionsDefault, oChartOptions)
	    	});
    	},
    	'json'
    );
};

/** @} */
