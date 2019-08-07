<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModGeneralDb extends BxDolModuleDb
{
    protected $_oConfig;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
        $this->_oConfig = $oConfig;
    }

    public function getContentInfoById ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_ENTRIES']) || empty($CNF['FIELD_ID']))
            return array();

        $sQuery = $this->prepare ("SELECT * FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_ID'] . "` = ?", $iContentId);
        return $this->getRow($sQuery);
    }

    function getEntriesBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`" . $CNF['TABLE_ENTRIES'] . "`.*";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1]['id'] = (int)$aParams['id'];

                $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "` = :id";
                break;

            case 'author':
            	$aMethod['name'] = 'getRow';
                $aMethod['params'][1]['author'] = (int)$aParams['author'];

                $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_AUTHOR'] . "` = :author";
                break;

            case 'search_ids':
                $this->_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
                break;

            case 'all_ids':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "`";
                $sOrderClause .=  "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ADDED'] . "` ASC";
                break;

            case 'all':
                $sOrderClause .=  "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ADDED'] . "` ASC";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ENTRIES'] . "` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
		return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getEntriesByAuthor ($iProfileId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_AUTHOR'] . "` = ? ORDER BY `" . $this->_oConfig->CNF['FIELD_ADDED'] . "` DESC", $iProfileId);
        return $this->getAll($sQuery);
    }

    public function getEntriesNumByAuthor ($iProfileId)
    {
        $sQuery = $this->prepare ("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_AUTHOR'] . "` = ?", $iProfileId);
        return $this->getOne($sQuery);
    }
    
    public function updateEntriesBy($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_ENTRIES']) || empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function publishEntries()
    {
        $CNF = $this->_oConfig->CNF;

        $aEntries = $this->getAll("SELECT `id`, `" . $CNF['FIELD_PUBLISHED'] . "`, FROM_UNIXTIME(`" . $CNF['FIELD_PUBLISHED'] . "`)  FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE `" . $CNF['FIELD_PUBLISHED'] . "` > `" . $CNF['FIELD_ADDED'] . "` AND `" . $CNF['FIELD_STATUS'] . "` = 'awaiting'");
        if(empty($aEntries) || !is_array($aEntries))
            return false;

        $iNow = time();
        $aResult = array();
        foreach($aEntries as $aEntry)
            if($aEntry[$CNF['FIELD_PUBLISHED']] <= $iNow) 
                $aResult[] = $aEntry[$CNF['FIELD_ID']];

        $sSet = "`" . $CNF['FIELD_ADDED'] . "`=`" . $CNF['FIELD_PUBLISHED'] . "`, `" . $CNF['FIELD_STATUS'] . "` = 'active'";
        if(isset($CNF['FIELD_CHANGED']))
            $sSet .= ", `" . $CNF['FIELD_CHANGED'] . "`=`" . $CNF['FIELD_PUBLISHED'] . "`";

        return count($aResult) == (int)$this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $sSet . " WHERE `id` IN (" . $this->implode_escape($aResult) . ")") ? $aResult : false;
    }

    public function alterFulltextIndex ()
    {
        $CNF = $this->_oConfig->CNF;        

        $bFulltextIndex = false;
        $aIndexes = $this->getAll("SHOW INDEXES FROM `" . $CNF['TABLE_ENTRIES'] . "`");

        foreach ($aIndexes as $r) {
            if ($CNF['TABLE_ENTRIES_FULLTEXT'] == $r['Key_name']) {
                $bFulltextIndex = true;
                break;
            }
        }

        if ($bFulltextIndex)
            $this->query("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` DROP INDEX `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "`");

        $sFields = getParam($CNF['PARAM_SEARCHABLE_FIELDS']);
        if (!$sFields || !($aFields = explode(',', $sFields)))
            return true;

        $sFields = '';
        foreach ($aFields as $s)
            $sFields .= "`$s`,";

        return $this->query("ALTER TABLE `" . $CNF['TABLE_ENTRIES'] . "` ADD FULLTEXT `" . $CNF['TABLE_ENTRIES_FULLTEXT'] . "` (" . trim($sFields, ', ') . ")");
    }

    protected function _getEntriesBySearchIds($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod['name'] = 'getColumn';

        $sSelectClause = " DISTINCT `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "`";

        if (!empty($aParams['start']) && !empty($aParams['per_page']))
            $sLimitClause = $this->prepareAsString("?, ?", $aParams['start'], $aParams['per_page']);
        elseif (!empty($aParams['per_page']))
            $sLimitClause = $this->prepareAsString("?", $aParams['per_page']);

        $sWhereConditions = "1";
        foreach($aParams['search_params'] as $sSearchParam => $aSearchParam) {
            $sSearchValue = "";
            switch ($aSearchParam['operator']) {
                case 'like':
                    $sSearchValue = " LIKE " . $this->escape("%" . preg_replace('/\s+/', '%', $aSearchParam['value']) . "%");
                    break;

                case 'in':
                    $sSearchValue = " IN (" . $this->implode_escape($aSearchParam['value']) . ")";
                    break;

                case 'and':
                    $iResult = 0;
                    if (is_array($aSearchParam['value']))
                        foreach ($aSearchParam['value'] as $iValue)
                            $iResult |= pow (2, $iValue - 1);
                    else 
                        $iResult = (int)$aSearchParam['value'];

                    $sSearchValue = " & " . $iResult . "";
                    break;

                case 'locate':
                    if(!isset($CNF['OBJECT_METATAGS']))
                        break;
                    if ($aSearchParam['type'] == 'location_radius'){
                        if ($aSearchParam['value']['string'] != ''){
                            list($fLatitude, $fLongitude, $sCountry, $sState, $sCity, $sZip, $sStreet, $sStreetNumber, $iRadius) = $aSearchParam['value']['array'];
                            $aBounds = bx_get_location_bounds_latlng($fLatitude, $fLongitude, $iRadius);   
                            $aSql = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])->locationsGetAsSQLPart($CNF['TABLE_ENTRIES'], $CNF['FIELD_ID'], '', '', '', '', $aBounds);
                        }
                    }
                    else{
                        list($fLatitude, $fLongitude, $sCountry, $sState, $sCity, $sZip) = $aSearchParam['value']['array'];
                        $aSql = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])->locationsGetAsSQLPart($CNF['TABLE_ENTRIES'], $CNF['FIELD_ID'], $sCountry, $sState, $sCity, $sZip);
                    }
                    if(!empty($aSql['where'])) {
                        $sWhereConditions .= $aSql['where'];

                        if(!empty($aSql['join']))
                            $sJoinClause .= $aSql['join'];
                    }
                    break;
                    
                case 'between':
                    if(!is_array($aSearchParam['value']) || count($aSearchParam['value']) != 2) 
                        break;

                    $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "` >= :" . $sSearchParam . "_from";
                    $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "` <= :" . $sSearchParam . "_to";

                    $aMethod['params'][1][$sSearchParam . "_from"] = $aSearchParam['value'][0]; 
                    $aMethod['params'][1][$sSearchParam . "_to"] = $aSearchParam['value'][1]; 
                    break;

                default:
                    if(empty($aSearchParam['operator']))
                        break;

                    $sSearchValue = " " . $aSearchParam['operator'] . " :" . $sSearchParam;
                    $aMethod['params'][1][$sSearchParam] = $aSearchParam['value'];
            }

            if(!empty($sSearchValue))
                $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "`" . $sSearchValue;
        }

        $sWhereClause .= " AND (" . $sWhereConditions . ")"; 

        $this->_getEntriesBySearchIdsOrder($aParams, $sOrderClause);
    }

    protected function _getEntriesBySearchIdsOrder($aParams, &$sOrderClause)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParams['search_params']['order']) || !is_array($aParams['search_params']['order'])) 
            $aParams['search_params']['order'] = array(
                array('table' => $CNF['TABLE_ENTRIES'], 'field' => $CNF['FIELD_ADDED'], 'direction' => 'DESC')
            );

        $aOrders = array();
        foreach($aParams['search_params']['order'] as $aOrder) 
            $aOrders[] = "`" . (isset($aOrder['table']) ? $aOrder['table'] : $CNF['TABLE_ENTRIES']) . "`.`" . (!empty($aOrder['field']) ? $aOrder['field'] : $CNF['FIELD_ADDED']) . "` " . (!empty($aOrder['direction']) ? strtoupper($aOrder['direction']) : 'DESC');

        $sOrderClause .= implode(', ', $aOrders);
    }
}

/** @} */
