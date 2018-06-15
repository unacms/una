/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Charts Charts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxCharts(oOptions) {
    this._sObjName = undefined == oOptions.sObjName ? 'oCharts' : oOptions.sObjName;    // javascript object name, to run current object instance from onTimer
    this._sChartName = oOptions.sChartName == undefined ? '' : oOptions.sChartName;
    this._sActionsUri = oOptions.sActionUri;
    this._sKeyGraph = 'bx_chart_growth_graph_' + this._sChartName;
    this._oChartOptionsDefault = {};
    var $this = this;
    $(document).ready(function () {
        $this.loadData();
    });
}

BxCharts.prototype.loadData = function () {
    var $this = this;
    var oGraph = $('.' + this._sKeyGraph);
    var oGraphWrp = oGraph.parents('div:first');
    var oGraphErr = oGraphWrp.find('.bx-chart-growth-graph-error').hide();

    bx_loading(oGraphWrp, true);

    $.get(
    	this._sActionsUri + 'get_chart_data/' + $this._sChartName + '/',
    	{
    	},
    	function (oData) {
    	    bx_loading(oGraphWrp, false);

    	    if (oData.error != undefined) {
    	        if ($this._oChart)
    	            $this._oChart.destroy();

    	        oGraphErr.html(oData.error).show();
    	        return;
    	    }
    	    var oDataForChart = oData.data || false;
    	    var oChartOptions = oData.options || {};

    	    if ($this._oChart)
    	        $this._oChart.destroy();
    	   
    	    $this._oChart = new Chart(oGraph, {
    	        type: oData.type != undefined ? oData.type : 'line',
    	        data: oDataForChart,
    	        options: $.extend({}, $this._oChartOptionsDefault, oChartOptions)
    	    });

    	    if (oData.links) {
    	        oGraph.click(function (evt) {
    	            var activePoint = $this._oChart.getElementAtEvent(evt)[0];
    	            var data = activePoint._chart.data;
    	            var datasetIndex = activePoint._datasetIndex;
    	            var label = data.datasets[datasetIndex].label;
    	            var value = data.datasets[datasetIndex].data[activePoint._index];
    	            location.href = oData.links[activePoint._index];
    	        });
    	    }

    	},
    	'json'
    );
};

/** @} */
