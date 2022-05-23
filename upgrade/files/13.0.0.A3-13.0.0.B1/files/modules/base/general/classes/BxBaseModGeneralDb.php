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
                $aMethod['params'][1]['author'] = (int)$aParams['author'];

                $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_AUTHOR'] . "` = :author";
                break;

            case 'conditions':
                if(empty($aParams['conditions']))
                    break;

                if(isset($aParams['count']) && $aParams['count'] === true) {
                    $aMethod['name'] = 'getOne';
                    $sSelectClause = 'COUNT(`' . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . '`)';
                }

                if(is_array($aParams['conditions']))
                    $sWhereClause .= " AND " . $this->arrayToSQL($aParams['conditions'], ' AND ');
                else if(is_string($aParams['conditions']) && !empty($aParams['bindings']) && is_array($aParams['bindings'])) {
                    $sWhereClause .= $aParams['conditions'];
                    
                    if(!is_array($aMethod['params'][1]))
                        $aMethod['params'][1] = array();

                    $aMethod['params'][1] = array_merge($aMethod['params'][1], $aParams['bindings']);
                }
                break;

            case 'search_ids':
                $this->_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
                bx_alert('system', 'search_ids', 0, 0, array('module_name' => $this->_oConfig->getName(), 'params' => &$aParams, 'select_clause' => &$sSelectClause, 'join_clause' => &$sJoinClause, 'where_clause' => &$sWhereClause, 'order_clause' => &$sOrderClause, 'limit_clause' => &$sLimitClause, 'bindings' => &$aMethod['params'][1]));
                break;

            case 'all_ids':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . "`";
                $sOrderClause .=  "`" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ADDED'] . "` ASC";
                break;

            case 'all':
                if(isset($aParams['count']) && $aParams['count'] === true) {
                    $aMethod['name'] = 'getOne';
                    $sSelectClause = 'COUNT(`' . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_ID'] . '`)';
                }

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

    public function getEntriesNumByContext ($iProfileId)
    {
        $sQuery = $this->prepare ("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `" . $this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO'] . "` = ?", - $iProfileId);
        return $this->getOne($sQuery);
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
    
    public function getEntriesNumByParams ($aParams = [])
    {
        $sSql = "SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE 1";
        
        foreach($aParams as $aValue){
            $sSql .= ' AND `' . $aValue['key'] ."` " . $aValue['operator'] . " '" . $aValue['value'] . "'";
        }
        
        $sQuery = $this->prepare($sSql);
        return $this->getOne($sQuery);
    }
    
    public function updateEntriesBy($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_ENTRIES']) || empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function updateStatusAdmin($iContentId, $isActive)
    {
        $CNF = $this->_oConfig->CNF;
        if (!isset($CNF['FIELD_STATUS_ADMIN']))
            return false;
        
        $sQuery = $this->prepare("UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET `" . $CNF['FIELD_STATUS_ADMIN'] . "` = ? WHERE `" . $CNF['FIELD_ID'] . "` = ?", $isActive ? 'active' : 'hidden', $iContentId);
        return $this->query($sQuery);
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
    
    public function deleteNestedById ($iNestedId, $sTableKey, $sTableName)
	{
		return $this->query("DELETE FROM `" . $sTableName . "` WHERE `" . $sTableKey . "` = :content_id", array('content_id' => $iNestedId));
	}
   
    function getNestedBy($aParams, $sTableName)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`" . $sTableName . "`.*";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1]['id'] = (int)$aParams['id'];

                $sWhereClause .= " AND `" . $sTableName . "`.`" . $aParams['key_name'] . "` = :id";
                break;
                
            case 'content_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][2]['id'] = (int)$aParams['id'];
                $aMethod['params'][1] = $aParams['key_name'];
                $sWhereClause .= " AND `" . $sTableName . "`.`content_id` = :id";
                $sOrderClause = $aParams['key_name'];
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $sTableName . "` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
		return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
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
            if(empty($aSearchParam['operator']))
                continue;

            $sSearchValue = "";
            switch ($aSearchParam['operator']) {
                case 'like':
                    if(is_array($aSearchParam['value'])) {
                        $sSubCondition = "0";
                        foreach($aSearchParam['value'] as $sValue)
                            if(!empty($sValue))
                                $sSubCondition .= " OR `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "` LIKE " . $this->_getEbsiLike($sValue);

                        if($sSubCondition != "0")
                            $sWhereConditions .= " AND (" . $sSubCondition . ")";
                    }
                    else
                        $sSearchValue = " LIKE " . $this->_getEbsiLike($aSearchParam['value']);
                    break;

                case 'in':
                    $sSearchValue = " IN (" . $this->implode_escape($aSearchParam['value']) . ")";
                    break;

                case 'not in':
                    $sSearchValue = " NOT IN (" . $this->implode_escape($aSearchParam['value']) . ")";
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
                        list($fLatitude, $fLongitude, $sCountry, $sState, $sCity, $sZip, $sStreet, $sStreetNumber, $iRadius) = $aSearchParam['value']['array'];
                        if ($fLatitude && $fLongitude && $iRadius) {
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

                    list($mixedMin, $mixedMax) = $aSearchParam['value'];

                    if(!empty($mixedMin)) {
                        $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "` >= :" . $sSearchParam . "_from";
                        
                        $aMethod['params'][1][$sSearchParam . "_from"] = $mixedMin; 
                    }

                    if(!empty($mixedMax)) {
                        $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "` <= :" . $sSearchParam . "_to";

                        $aMethod['params'][1][$sSearchParam . "_to"] = $mixedMax; 
                    }
                    break;

                default:
                    $sSearchValue = " " . $aSearchParam['operator'] . " :" . $sSearchParam;
                    $aMethod['params'][1][$sSearchParam] = $aSearchParam['value'];
            }

            if(!empty($sSearchValue))
                $sWhereConditions .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $sSearchParam . "`" . $sSearchValue;
        }

        $sWhereClause .= " AND (" . $sWhereConditions . ")"; 

        $this->_addCustomConditions($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);

        $this->_getEntriesBySearchIdsOrder($aParams, $sOrderClause);
    }

    protected function _addCustomConditions($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $this->_addConditionsForAuthorStatus($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);

        $this->_addConditionsForCf($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);
    }
    
    protected function _addConditionsForAuthorStatus($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;
        if (empty($CNF['FIELD_AUTHOR']))
            return;

        if (!empty($aParams['show_all_content']))
            return;

        $sJoinClause .= " INNER JOIN `sys_profiles` as `p` ON (`p`.`id` = `{$CNF['TABLE_ENTRIES']}`.`{$CNF['FIELD_AUTHOR']}` AND `p`.`status` = 'active') ";
    }

    protected function _addConditionsForCf($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['FIELD_CF']))
            return;

        $oCf = BxDolContentFilter::getInstance();
        if(!$oCf->isEnabled()) 
            return;

        $sWhereClause .= $oCf->getSQLParts($CNF['TABLE_ENTRIES'], $CNF['FIELD_CF']);
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

    protected function _getEbsiLike($sValue)
    {
        return $this->escape("%" . preg_replace('/\s+/', '%', $sValue) . "%");
    }
}

/** @} */
