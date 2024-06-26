<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */


class BxDolChartGrowth extends BxDolChart
{
    protected function __construct($aObject)
    {
        parent::__construct($aObject);
    }

    protected function _getDataByInterval($iFrom, $iTo)
    {
        $sFieldDate = $this->_aObject['field_date_dt'] ? $this->_aObject['field_date_dt'] : $this->_aObject['field_date_ts'];
        $this->addMarkers([
            'field_date' => "`" . $sFieldDate . "`",
            'field_date_formatted' => "DATE_FORMAT(" . ($this->_aObject['field_date_dt'] ? "`{$this->_aObject['field_date_dt']}`" : "FROM_UNIXTIME(`{$this->_aObject['field_date_ts']}`)") . ", '%Y-%m-%d')",
            'object' => $this->_aObject['object'],
            'table' => "`{$this->_aObject['table']}`",
            'where_inteval' => "AND `" . $this->_aObject['table'] . "`.`" . $sFieldDate . "` >= :from AND `" . $this->_aObject['table'] . "`.`" . $sFieldDate . "` <= :to"
        ]);

        // build query
        $sQuery = $this->_getQuery();
        $sQueryInterval = $this->replaceMarkers($sQuery);

        $aBindings = array(
            'from' => $this->_aObject['field_date_dt'] ? $this->_getDate($iFrom) . ' 00:00:00' : $iFrom,
            'to' => $this->_aObject['field_date_dt'] ? $this->_getDate($iTo) . ' 23:59:59' : $iTo + 24*3600 - 1,
        );

        // get data
        if ($this->_aObject['column_date'] >= 0)
            $aData = $this->_oDb->getAllWithKey($sQueryInterval, $this->_aObject['column_date'], $aBindings, PDO::FETCH_NUM);
        else
            $aData = $this->_oDb->getAll($sQueryInterval, array(), $aBindings, PDO::FETCH_NUM);

        if (!$aData)
            return false;

        // get interval first value
        $this->addMarkers([
            'where_inteval' => "AND `" . $this->_aObject['table'] . "`.`" . $sFieldDate . "` < :from"
        ]);
        $aDataInit = $this->_oDb->getAll($this->replaceMarkers($sQuery), array(
            'from' => $aBindings['from']
        ));

        $iCountInit = 0;
        foreach($aDataInit as $aDataItem) 
            $iCountInit += $aDataItem[$this->_aObject['object']];

        // fill in missed days and convert values to numbers
        if ($this->_aObject['column_date'] >= 0) {
            $iCountCurrent = 0;
            $aDataSlice = array_slice($aData, 0, 1);
            $iColumnsNum = count(array_pop($aDataSlice));
            for ($i = $iFrom ; $i <= ($iTo + 24*3600 - 1); $i += 24*60*60) {
                $sDate = $this->_getDate($i);
                $aRow = array ();
                for ($j = 0 ; $j < $iColumnsNum ; ++$j) {
                    if($this->_aObject['column_date'] != $j) {
                        $iCountCurrent += isset($aData[$sDate]) ? (int)$aData[$sDate][$j] : 0;
                        $aRow[$j] = $iCountInit + $iCountCurrent;
                    }
                    else
                        $aRow[$j] = $sDate;
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
}

/** @} */
