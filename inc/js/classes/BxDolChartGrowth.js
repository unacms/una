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

    var $this = this;
    $(document).ready(function() {
    	$this.loadData();
    });
}

BxDolChartGrowth.prototype.loadData = function()
{
	var $this = this;
	var oGraphWrp = $('#' + this._sKeyGraph).parents('div:first');

    $('#' + this._sKeyObjects).attr('disabled', true);

    bx_loading(oGraphWrp, true);

    $.get(
    	this._sActionsUrl,
    	{
    		object: $('#' + this._sKeyObjects).val(),
    		action: 'load_data_by_interval',
    		from: $.datepicker.formatDate($this._sDateFormat, $('#' + $this._sKeyDateFrom).datepicker('getDate')),
            to: $.datepicker.formatDate($this._sDateFormat, $('#' + $this._sKeyDateTo).datepicker('getDate'))
    	},
    	function(oData) {
    		$('#' + $this._sKeyObjects).attr('disabled', false);

            bx_loading(oGraphWrp, false);

            if(oData.error != undefined) {
                alert(oData.error);
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

	    	$this._oChart = new Chart($('#' + $this._sKeyGraph), {
	    		type: 'line',
	    		data: {
	            	labels: oChartLabels,
	            	datasets: [{
	            		label: oData.title,
	            		data: oChartData
	            	}]
	            },
	    		options: {}
	    	});
    	},
    	'json'
    );
};

/** @} */
