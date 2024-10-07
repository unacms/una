<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Courses module database queries
 */
class BxCoursesDb extends BxBaseModGroupsDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getContentNodes($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tcn`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = "AND `tcn`.`id`=:id";
                break;

            case 'id_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sSelectClause .= ", `tcs`.*";
                $sWhereClause = "AND `tcn`.`id`=:id";
                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_CNT_STRUCTURE'] . "` AS `tcs` ON `tcn`.`id`=`tcs`.`node_id`";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;
        
        if(!empty($sLimitClause))
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_CNT_NODES'] . "` AS `tcn` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function updateContentNodes($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_CNT_NODES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function getContentStructure($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tcs`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = "AND `tcs`.`id`=:id";
                break;

            case 'node_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'node_id' => $aParams['node_id']
                ];

                $sWhereClause = "AND `tcs`.`node_id`=:node_id";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;
        
        if(!empty($sLimitClause))
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_CNT_STRUCTURE'] . "` AS `tcs` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getContentStructureOrderMax($iEntryId, $iParentId)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->getOne("SELECT IFNULL(MAX(`order`), 0) FROM `" . $CNF['TABLE_CNT_STRUCTURE'] . "` WHERE `entry_id`=:entry_id AND `parent_id`=:parent_id", [
            'entry_id' => $iEntryId,
            'parent_id' => $iParentId
        ]);
    }
    
    public function insertContentStructureNode($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_CNT_STRUCTURE'] . "` SET " . $this->arrayToSQL($aParamsSet)) > 0 ? (int)$this->lastId() : false;
    }

    public function updateContentStructureNode($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_CNT_STRUCTURE'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }
    
    public function updateContentStructureCounters($iParentId, $iLevel, $iAdd)
    {
        $aParent = $this->getContentStructure(['sample' => 'node_id', 'node_id' => $iParentId]);
        if(empty($aParent) || !is_array($aParent))
            return;

        $sKeyLevel = 'cn_l' . $iLevel;
        $this->updateContentStructureNode([$sKeyLevel => $aParent[$sKeyLevel] + $iAdd], ['id' => $aParent['id']]);
        
        if(!empty($aParent['parent_id']))
            $this->updateContentStructureCounters($aParent['parent_id'], $iLevel, $iAdd);
    }

    public function deleteContentStructureNode($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_CNT_STRUCTURE'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function getContentData($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tcd`.*";
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = "AND `tcd`.`id`=:id";
                break;
            
            case 'content':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'content_type' => $aParams['content_type'],
                    'content_id' => $aParams['content_id']
                ];

                $sWhereClause = "AND `tcd`.`content_type`=:content_type AND `tcd`.`content_id`=:content_id";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;
        
        if(!empty($sLimitClause))
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_CNT_DATA'] . "` AS `tcd` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getContentDataOrderMax($iEntryId, $iNodeId)
    {
        $CNF = &$this->_oConfig->CNF;

        return (int)$this->getOne("SELECT IFNULL(MAX(`order`), 0) FROM `" . $CNF['TABLE_CNT_DATA'] . "` WHERE `entry_id`=:entry_id AND `node_id`=:node_id", [
            'entry_id' => $iEntryId,
            'node_id' => $iNodeId
        ]);
    }

    public function insertContentData($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_CNT_DATA'] . "` SET " . $this->arrayToSQL($aParamsSet)) > 0 ? (int)$this->lastId() : false;
    }

    public function deleteContentData($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_CNT_DATA'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }
}

/** @} */
