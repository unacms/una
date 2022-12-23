/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Analytics Analytics
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAnalytics(oOptions) {
    this._sContainerId = 'bx_analytics_container';
    this._sModuleSelector = '#' + this._sContainerId + ' .bx_analytics_module_selector';
    this._sReportSelector = '#' + this._sContainerId + " .bx_analytics_report_selector";
    this._sContainerDataSelector = '#' + this._sContainerId + " .bx_analytics_data";
    this._sCanvasSelector = '#' + this._sContainerId + " .bx_analytics_grath";
    this._sDatePickerSelector = '#' + this._sContainerId + " .bx_analytics_date_picker";
    this._sExportUrlSelector = '#' + this._sContainerId + " .bx_analytics_export_url";
    this._sActionsUri = oOptions.sActionUri;
    this._aStrings = oOptions.aStrings;
    var $this = this;
    $(document).ready(function () {
        $this.init(); 
    });
}

BxAnalytics.prototype.init = function () {
    var $this = this;
    $($this._sModuleSelector).change(function () {
        $this.reloadReports();
    });
    $($this._sReportSelector).change(function () {
        $this.reloadData();
    });
    $this.reloadReports();

    $($this._sDatePickerSelector).daterangepicker({
        opens: 'left',
        locale: {
            "format": "DD/MM/YYYY"
        },
    }, function (start, end, label) {
        $this.reloadData();
    });

}

BxAnalytics.prototype.reloadReports = function () {
    var $this = this;
    $.getJSON($this._sActionsUri + 'GetReports/' + $($this._sModuleSelector).val() + '/', function (aReports) {
        $($this._sReportSelector).empty();
        $.each(aReports, function (key, value) {
            $($this._sReportSelector).append('<option value="' + key + '">' + value + '</option>');
        });
        $this.reloadData();
    });
};

BxAnalytics.prototype.reloadData = function () {
    var $this = this;
    bx_loading($($this._sContainerDataSelector), true);
    $sUrl = $this._sActionsUri + 'GetReportsData/' + $($this._sModuleSelector).val() + '/' + $($this._sReportSelector).val() + '/' + $($this._sDatePickerSelector).data('daterangepicker').startDate.format('YYYY-MM-DD') + '/' + $($this._sDatePickerSelector).data('daterangepicker').endDate.format('YYYY-MM-DD') + '/';
    $($this._sExportUrlSelector).attr('href', $sUrl + 'csv/');
    $.getJSON($sUrl, function (oData) {
        Chart.defaults.global.layout = {
            padding: {
                left: 0,
                right: 0,
                top: 0,
                bottom: 0
            }
        }
		
        Chart.defaults.scale.ticks.callback = function (label, index, labels) {
            if (label.length > 10)
                return label.slice(0, 10) + '...';
            else
                return label;
        };

        bx_loading($($this._sContainerDataSelector), false);
        var oDataForChart = oData.data || false;
        var oChartOptions = oData.options || oOptionsDefault;
        if ($this._oChart)
            $this._oChart.destroy();
        $this._oChart = new Chart($($this._sCanvasSelector), {
            type: oData.type != undefined ? oData.type : 'line',
            data: oDataForChart,
            options: $.extend({}, $this._oChartOptionsDefault, oChartOptions)
        });
        $this.dataToTable(oData);
    });
};

BxAnalytics.prototype.dataToTable = function (oDataIn) {
    var $this = this;
    var oData = oDataIn.data;
    var sHtml = '<table>';
    var sCol1 = oDataIn.strings[0];

    var iColumnCount = oData.datasets.length;
    sHtml += '<thead><tr><th> ' + sCol1 + '</th>';
    $.each(oData.datasets, function (iDx, oItem) {
        var sText = oItem.label;
        sHtml += '<th>' + (oItem.label != '' ? oItem.label : oDataIn.strings[1]) + '</th>';
    });

    sHtml += '</tr></thead>';
    var iK = 0;
    
    oData.datasets[0].data.forEach(function (oItemData, iDx) {
        var sTxt = "";
        if (oDataIn.options.scales.xAxes[0].type == 'time')
            sTxt = oData.datasets[0].data[iDx].x;
        else{
            sTxt = oData.labels[iDx];
        }
        if (oDataIn.links && oDataIn.links.length > 0) {
            sTxt = "<a href='" + oDataIn.links[iDx] + "'>" + sTxt + "</a>"
        }
        sHtml += '<tr><td>&nbsp;'+  sTxt + '</td>';
        for (i = 0; i < iColumnCount; i++) {
            var sText = '';
            if (typeof oData.datasets[i].data[iDx] === 'object') {
                sText = oData.datasets[i].data[iDx].y;
            }
            else {
                sText = oData.datasets[i].data[iDx];
            }
            sHtml += '<td>' + sText + '</td>';
        }
        sHtml += '</tr>';
        iK++;
    });

    sHtml += '</tr><tbody></table>';
    if ($this._oTable)
        $this._oTable.destroy();
    $('.bx_analytics_table').html(sHtml);

    var $aOrder = [];
    if (oDataIn.options.scales.xAxes[0].type == 'time') {
        $aOrder = [[0, 'desc']];
    }
    $this._oTable = $('.bx_analytics_table').DataTable({ "stripeClasses": ['bx-def-color-bg-hl', ''], dom: '<"top"i>rt<"bottom"flp><"clear">', paging: true, searching: false, ordering: true, order: $aOrder });
   
};

/** @} */