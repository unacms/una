<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */


class BxDolChart extends BxDolFactory implements iBxDolFactoryObject
{
    protected $_oDb;

    protected $_sObject;
    protected $_aObject;

    protected $_sStatusActive;

    /**
     * Constructor
     * @param $aObject array of chart options
     */
    protected function __construct($aObject)
    {
        parent::__construct();

        $this->_oDb = new BxDolChartQuery($this->_aObject);

        $this->_sObject = $aObject['object'];
        $this->_aObject = $aObject;

        $this->_sStatusActive = 'active';
    }

    /**
     * Get editor object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false, $oTemplate = false)
    {
        if(isset($GLOBALS['bxDolClasses']['BxDolChart!' . $sObject]))
            return $GLOBALS['bxDolClasses']['BxDolChart!' . $sObject];

        $aObject = BxDolChartQuery::getChartObject($sObject);
        if(!$aObject || !is_array($aObject))
            return false;

        $sClass = 'BxDolChart';
        if(!empty($aObject['class_name'])) {
            $sClass = $aObject['class_name'];
            if(!empty($aObject['class_file']))
                require_once(BX_DIRECTORY_PATH_ROOT . $aObject['class_file']);
        }

        $o = new $sClass($aObject, $oTemplate);
        return ($GLOBALS['bxDolClasses']['BxDolChart!' . $sObject] = $o);
    }

    public function actionLoadDataByInterval()
    {
        $mixedResult = $this->checkAllowedView();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return array('error' => $mixedResult);

        $iFrom = $this->_getTimestamp(bx_get('from'));
        $iTo = $this->_getTimestamp(bx_get('to'), true);
        if(!$iFrom || !$iTo)
            return echoJson(array('error' => _t('_Error Occured')));

        $aData = $this->_getDataByInterval($iFrom, $iTo);
        if(empty($aData) || !is_array($aData))
            return echoJson(array('error' => _t('_Empty')));

        return echoJson(array (
            'title' => _t($this->_aObject['title']),
            'data' => $aData,
            'hide_date_range' => $this->_aObject['field_date_dt'] || $this->_aObject['field_date_ts'] ? false : true,
            'column_date' => $this->_aObject['column_date'] >= 0 ? $this->_aObject['column_date'] : false,
            'column_count' => $this->_aObject['column_count'] >= 0 ? $this->_aObject['column_count'] : false,
            'type' => $this->_aObject['type'] ? $this->_aObject['type'] : 'line',
            'options' => $this->_aObject['options'] ? unserialize($this->_aObject['options']) : false,
        ));
    }

    public function checkAllowedView($isPerformAction = false)
    {
        return BxDolService::call('system', 'check_allowed_view', array($isPerformAction), 'TemplChartServices');
    }

    protected function _getQuery()
    {
        if(!empty($this->_aObject['query'])) 
            return $this->_aObject['query'];

        $sWhereClause = "";
        if(!empty($this->_aObject['field_status'])) {
            $aStatusFields = explode(',', $this->_aObject['field_status']);
            foreach($aStatusFields as $sStatusField)
                $sWhereClause .= " AND `" . $sStatusField . "`='" . $this->_sStatusActive . "'";
        }

        return "SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} WHERE 1 " . $sWhereClause . " {where_inteval} GROUP BY `period` ORDER BY {field_date} ASC";
    }

    protected function _getTimestamp($sDate, $isNowIfError = false)
    {
        $aDate = explode('-', $sDate); // YYYY-MM-DD
        if(!$aDate || empty($aDate[0]) || empty($aDate[1]) || empty($aDate[2]) || !(int)$aDate[0] || !(int)$aDate[1] || !(int)$aDate[2])
            return $isNowIfError ? time() : false;

        return mktime(0, 0, 0, $aDate[1], $aDate[2], $aDate[0]);
    }

    protected function _getDate($iDate)
    {
        return date('Y-m-d', $iDate);
    }
}

/** @} */
