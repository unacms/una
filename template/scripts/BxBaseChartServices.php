<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Services for chart objects functionality
 * @see BxDolChart
 */
class BxBaseChartServices extends BxDol
{
    public function serviceCheckAllowedView($isPerformAction = false)
    {
        $iProfileId = bx_get_logged_profile_id();

        $aCheck = checkActionModule($iProfileId, 'chart view', 'system', $isPerformAction);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }
    
    public function serviceGetChartGrowth()
    {
        $mixedResult = BxDolService::call('system', 'check_allowed_view', array(), 'TemplChartServices');
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return '';

        $sDateFrom = date('Y-m-d', time() - 30*24*60*60);
        $sDateTo = date('Y-m-d', time());

        $aForm = array(
            'form_attrs' => array(
                'id' => 'bx_chart_controls',
                'action' => ''
            ),
            'inputs' => array (
                'object' => array(
                    'type' => 'select',
                    'name' => 'object',
                    'caption' => _t('_sys_chart_growth_object'),
                    'info' => '',
                    'value' => '',
                    'values' => array(),
                    'required' => '0',
                    'attrs' => array(
                        'id' => 'bx_chart_growth_objects',
                        'onchange' => 'oBxDolChartGrowth.loadData()'
                    ),
                ),
                'date_from' => array(
                    'type' => 'datepicker',
                    'name' => 'date_from',
                    'caption' => _t('_sys_chart_growth_date_from'),
                    'info' => '',
                    'value' => $sDateFrom,
                    'values' => array(),
                    'required' => '0',
                    'attrs' => array(
                		'id' => 'bx_chart_growth_date_from',
                        'onchange' => 'oBxDolChartGrowth.loadData()'
                    ),
                ),
                'date_to' => array(
                    'type' => 'datepicker',
                    'name' => 'date_to',
                    'caption' => _t('_sys_chart_growth_date_to'),
                    'info' => '',
                    'value' => $sDateTo,
                    'values' => array(),
                    'required' => '0',
                    'attrs' => array(
                		'id' => 'bx_chart_growth_date_to',
                        'onchange' => 'oBxDolChartGrowth.loadData()'
                    ),
                )
            )
        );

        $aObjects = BxDolChartQuery::getChartObjects();
        foreach($aObjects as $aObject)
            $aForm['inputs']['object']['values'][] = array('key' => $aObject['object'], 'value' => _t($aObject['title']));

        $oForm = new BxTemplFormView($aForm);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('BxDolChartGrowth.js'));
        $oTemplate->addCss(array('chart.css'));

        return $oTemplate->parseHtmlByName('chart_growth.html', array(
        	'proto' => bx_proto(),
            'date_from' => $sDateFrom,
            'date_to' => $sDateTo,
            'controls' => $oForm->getCode()
        ));
    }

    //TODO: Continue from here.
    public function serviceGetChartStats()
    {
        $aTmplVarsItems = array();
        $aTmplVarsData = array();

        $aObjects = BxDolChartQuery::getChartObjects();
        foreach($aObjects as $aObject) {
            $sTitle = _t($aObject['title']);
            $sValie = BxDolChart::getObjectInstance($aObject['object'])->getDataByStatus();

            $aTmplVarsItems[] = array(
                'title' => $sTitle,
            	'value' => $sValie,
            );

            $aTmplVarsData[] = array(
            	'value' => $sValie,
	            'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
	            'highlight' => '',
	            'label' => bx_js_string($sTitle, BX_ESCAPE_STR_APOS),
            );
        }

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('chart.min.js'));
        $oTemplate->addCss(array('chart.css'));

        return $oTemplate->parseHtmlByName('chart_stats.html', array(
        	'bx_repeat:items' => $aTmplVarsItems,
            'chart_data' => json_encode($aTmplVarsData)
        ));
    }
}

/** @} */
