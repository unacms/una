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

        $sClass = 'BxTemplChart';
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

        $aData = $this->getDataByInterval($iFrom, $iTo);
        if(empty($aData) || !is_array($aData))
            return echoJson(array('error' => _t('_Empty')));

        return echoJson(array (
            'title' => _t($this->_aObject['title']),
            'data' => $aData,
            'hide_date_range' => $this->_aObject['field_date_dt'] || $this->_aObject['field_date_ts'] ? false : true,
            'column_date' => $this->_aObject['column_date'] >= 0 ? $this->_aObject['column_date'] : false,
            'column_count' => $this->_aObject['column_count'] >= 0 ? $this->_aObject['column_count'] : false,
            'type' => $this->_aObject['type'] ? $this->_aObject['type'] : 'AreaChart',
            'options' => $this->_aObject['options'] ? unserialize($this->_aObject['options']) : false,
        ));
    }

    public function checkAllowedView($isPerformAction = false)
    {
        return BxDolService::call('system', 'check_allowed_view', array($isPerformAction), 'TemplChartServices');
    }

    public function getDataByInterval($iFrom, $iTo)
    {
        // build query
        $sQuery = $this->_aObject['query'] ? $this->_aObject['query'] : "SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} WHERE {field_date} >= :from AND {field_date} <= :to GROUP BY `period` ORDER BY {field_date} ASC";
        $sQuery = bx_replace_markers($sQuery, array (
            'field_date_formatted' => "DATE_FORMAT(" . ($this->_aObject['field_date_dt'] ? "`{$this->_aObject['field_date_dt']}`" : "FROM_UNIXTIME(`{$this->_aObject['field_date_ts']}`)") . ", '%Y-%m-%d')",
            'object' => $this->_aObject['object'],
            'table' => "`{$this->_aObject['table']}`",
            'field_date' => "`" . ($this->_aObject['field_date_dt'] ? $this->_aObject['field_date_dt'] : $this->_aObject['field_date_ts']) . "`",
        ));

        $aBindings = array(
            'from' => $this->_aObject['field_date_dt'] ? $this->_getDate($iFrom) . ' 00:00:00' : $iFrom,
            'to' => $this->_aObject['field_date_dt'] ? $this->_getDate($iTo) . ' 23:59:59' : $iTo + 24*3600 - 1,
        );
        
        // get data
        if ($this->_aObject['column_date'] >= 0)
            $aData = $this->_oDb->getAllWithKey($sQuery, $this->_aObject['column_date'], $aBindings, PDO::FETCH_NUM);
        else
            $aData = $this->_oDb->getAll($sQuery, array(), $aBindings, PDO::FETCH_NUM);

        if (!$aData)
            return false;
    
        // fill in missed days and convert values to numbers
        if ($this->_aObject['column_date'] >= 0) {
            $aDataSlice = array_slice($aData, 0, 1);
            $iColumnsNum = count(array_pop($aDataSlice));
            for ($i = $iFrom ; $i <= ($iTo + 24*3600 - 1); $i += 24*60*60) {
                $sDate = $this->_getDate($i);
                $aRow = array ();
                for ($j = 0 ; $j < $iColumnsNum ; ++$j) {
                    $v = isset($aData[$sDate]) ? (int)$aData[$sDate][$j] : 0;
                    $aRow[$j] = ($j == $this->_aObject['column_date'] ? $sDate : $v);
                }
                $aData[$sDate] = $aRow;
            }
        } 
        else
            foreach ($aData as $k => $v)
                foreach ($aData[$k] as $kk => $vv)
                    if ($kk > 0)
                        $aData[$k][$kk] = (int)$aData[$k][$kk];

        // return values only
        ksort($aData);
        return array_values($aData);
    }

    public function getDataByStatus()
    {
        // build query
        $sQuery = $this->_aObject['query'] ? $this->_aObject['query'] : "SELECT COUNT(*) AS {object} FROM {table} WHERE 1" . (!empty($this->_aObject['query_status']) ? $this->_aObject['query_status'] : '');
        $sQuery = bx_replace_markers($sQuery, array (
            'object' => $this->_aObject['object'],
            'table' => "`{$this->_aObject['table']}`",
        ));

        return $this->_oDb->getOne($sQuery);
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
