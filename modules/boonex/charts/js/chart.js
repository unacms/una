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
    this._oChartOptionsDefault = {  };
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
    var sAdditionalParameters = "";
    if ($('.bx_chart_growth_selector_' + this._sChartName).length > 0) {
        sAdditionalParameters = '?m=' + $('.bx_chart_growth_selector_' + this._sChartName).val();
        $('.bx_chart_growth_selector_' + this._sChartName).change(function () {
            $this.loadData();
        });
    }

    bx_loading(oGraphWrp, true);

    $.get(
    	this._sActionsUri + 'get_chart_data/' + $this._sChartName + '/' + sAdditionalParameters +'',
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

    	    if (oData.links) {
    	        Chart.defaults.doughnut.legend.onClick = function (e, legendItem) {
    	            var iIndex = $this._oChart.data.datasets[0].backgroundColor.indexOf(legendItem.fillStyle);
    	            if (iIndex && oData.links[iIndex] && oData.links[iIndex] != "") {
    	                location.href = oData.links[iIndex];
    	            }
    	        };
    	    }

    	    Chart.defaults.doughnut.tooltips.callbacks.label = function (tooltipItem, data) {
    	        return data.labels[tooltipItem.index];
    	    };

    	    Chart.defaults.global.layout  = {
    	        padding: {
    	                left: 40,
    	                right: 40,
    	                top: 40,
    	                bottom: 20
    	        }
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
    	            if (activePoint) {
    	                var oDataChart = activePoint._chart.data;
    	                var iDatasetIndex = activePoint._datasetIndex;
    	                var sLabel = oDataChart.datasets[iDatasetIndex].label;
    	                var sValue = oDataChart.datasets[iDatasetIndex].data[activePoint._index];
    	                var sLink = oData.links[activePoint._index];
    	                if (sLink != "")
    	                    location.href = sLink;
    	            }
    	        });
    	    }
    	},
    	'json'
    );
};

/** @} */
