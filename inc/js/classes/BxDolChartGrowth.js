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

    var $this = this;
    $(document).ready(function() {
        google.setOnLoadCallback($this.loadData());
    });
}

BxDolChartGrowth.prototype.loadData = function()
{
	var $this = this;
	

	$('#' + this._sKeyGraph).html('');
    $('#' + this._sKeyObjects).attr('disabled', true);

    bx_loading(this._sKeyGraph, true);

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

            bx_loading($this._sKeyGraph, false);

            if(oData.error != undefined) {
                $('#' + $this._sKeyGraph).html('<div class="bx-def-padding bx-def-font-large bx-def-font-align-center">' + oData.error + '</div>');
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

            // convert dates
            if(oData.column_date !== false) {
                for (var i in oData.data) {    
                    var sDate = oData.data[i][oData.column_date];
                    var m = sDate.match(/(\d{4})-(\d{2})-(\d{2})/);
                    if(!m || !m[1] || !m[2] || !m[3])
                        continue;

                    var oDate = new Date(m[1],m[2]-1,m[3]);
                    oData.data[i][oData.column_date] = oDate;
                }
            } 

            // add data
            var oDataTable = new google.visualization.DataTable();                
            for (var i = 0 ; i < oData.data[0].length ; ++i) {
                var sType = 0 == i ? 'string' : 'number';
                var sLabel = '';
                if (false !== oData.column_date && i == oData.column_date)
                    sType = 'datetime'; 
                else if (false !== oData.column_count && i == oData.column_count)
                    sLabel = oData.title;
                oDataTable.addColumn(sType, sLabel);
            }
            oDataTable.addRows(oData.data);

            // define options
            var oOptions = {
              title: oData.title
            };

            if (oData.options != undefined)
                oOptions = jQuery.extend(oOptions, oData.options);

            // draw chart
            var chart = new google.visualization[oData.type]($('#' + $this._sKeyGraph)[0]);
            chart.draw(oDataTable, oOptions);
    	},
    	'json'
    );
};

/** @} */
