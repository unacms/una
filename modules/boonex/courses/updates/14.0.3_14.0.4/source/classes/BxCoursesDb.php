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

                $sSelectClause .= ", `tcs`.`parent_id`, `tcs`.`level`, `tcs`.`order`, `tcs`.`cn_l2`, `tcs`.`cn_l3`";
                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_CNT_STRUCTURE'] . "` AS `tcs` ON `tcn`.`id`=`tcs`.`node_id`";
                $sWhereClause = "AND `tcn`.`id`=:id";
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

    public function deleteContentNodes($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_CNT_NODES'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function deleteContentNodesWithTracks($iEntryId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `tcn`, `tcnu` USING `" . $CNF['TABLE_CNT_NODES'] . "` AS `tcn` LEFT JOIN `" . $CNF['TABLE_CNT_NODES2USERS'] . "` AS `tcnu` ON `tcn`.`id`=`tcnu`.`node_id` WHERE `tcn`.`entry_id`=:entry_id", [
            'entry_id' => $iEntryId
        ]);
    }

    public function insertContentNodes2Users($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_CNT_NODES2USERS'] . "` SET " . $this->arrayToSQL($aParamsSet) . " ON DUPLICATE KEY UPDATE `date`=UNIX_TIMESTAMP()") > 0 ? (int)$this->lastId() : false;
    }

    public function deleteContentNodes2Users($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_CNT_NODES2USERS'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function getContentStructure($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

        $sSelectClause = "`tcs`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

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
            
            case 'entry_id':
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sWhereClause = "AND `tcs`.`entry_id`=:entry_id";

                if(isset($aParams['level'])) {
                    $aMethod['params'][1]['level'] = $aParams['level'];
                        
                    $sWhereClause .= " AND `tcs`.`level`=:level";
                }
                break;

            case 'entry_id_full':
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sSelectClause .= ", `tcn`.`title`, `tcn`.`counters`";
                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_CNT_NODES'] . "` AS `tcn` ON `tcs`.`node_id`=`tcn`.`id`";
                $sWhereClause = "AND `tcs`.`entry_id`=:entry_id";

                if(isset($aParams['parent_id'])) {
                    $aMethod['params'][1]['parent_id'] = $aParams['parent_id'];
                        
                    $sWhereClause .= " AND `tcs`.`parent_id`=:parent_id";
                }

                if(isset($aParams['level'])) {
                    $aMethod['params'][1]['level'] = $aParams['level'];
                        
                    $sWhereClause .= " AND `tcs`.`level`=:level";
                }

                if(isset($aParams['status'])) {
                    $aMethod['params'][1]['status'] = $aParams['status'];
                        
                    $sWhereClause .= " AND `tcn`.`status`=:status";
                }

                if(isset($aParams['start'], $aParams['per_page']) && (int)$aParams['per_page'] != 0)
                    $sLimitClause = $aParams['start'] . ', ' . $aParams['per_page'];
                
                $sOrderClause = "`tcs`.`order` ASC";
                break;
                
            case 'entry_id_counters':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sSelectClause = "COUNT(`tcs`.`id`) AS `cn_l1`, SUM(`tcs`.`cn_l2`) AS `cn_l2`, SUM(`tcs`.`cn_l3`) AS `cn_l3`";
                $sWhereClause = "AND `tcs`.`entry_id`=:entry_id AND `tcs`.`level`='1'";
                $sGroupClause = "`tcs`.`entry_id`";
                break;
                
            case 'parent_id':
                $aMethod['params'][1] = [
                    'parent_id' => $aParams['parent_id']
                ];

                $sWhereClause = "AND `tcs`.`parent_id`=:parent_id";
                break;

            case 'user_passed':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'node_id' => $aParams['node_id']
                ];

                $sSelectClause = "`tcnu`.*";
                $sJoinClause = "INNER JOIN `" . $CNF['TABLE_CNT_NODES2USERS'] . "` AS `tcnu` ON `tcs`.`node_id`=`tcnu`.`node_id` AND `tcnu`.`node_id`=:node_id AND `tcnu`.`profile_id`=:profile_id";
                break;

            case 'user_track':
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'entry_id' => $aParams['entry_id'],
                    'node_id' => $aParams['node_id']
                ];

                $sJoinClause = "INNER JOIN `" . $CNF['TABLE_CNT_NODES2USERS'] . "` AS `tcnu` ON `tcs`.`node_id`=`tcnu`.`node_id` AND `tcnu`.`profile_id`=:profile_id";
                $sWhereClause = "AND `tcs`.`entry_id`=:entry_id AND `tcs`.`parent_id`=:node_id";
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;
        
        if(!empty($sLimitClause))
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_CNT_STRUCTURE'] . "` AS `tcs` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
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

            case 'entry_id':
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id']
                ];

                $sWhereClause = "AND `tcd`.`entry_id`=:entry_id";
                break;

            case 'node_id':
                $aMethod['params'][1] = [
                    'node_id' => $aParams['node_id']
                ];

                $sWhereClause = "AND `tcd`.`node_id`=:node_id";
                $sOrderClause = "`tcd`.`order` ASC";
                break;

            case 'entry_node_ids':
                $aMethod['params'][1] = [
                    'entry_id' => $aParams['entry_id'],
                    'node_id' => $aParams['node_id']
                ];

                $sWhereClause = "AND `tcd`.`entry_id`=:entry_id AND `tcd`.`node_id`=:node_id";

                if(isset($aParams['usage'])) {
                    $aMethod['params'][1]['usage'] = $aParams['usage'];

                    $sWhereClause .= " AND `tcd`.`usage`=:usage";
                }

                $sOrderClause = "`tcd`.`order` ASC";
                break;
                
            case 'siblings':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'order';
                $aMethod['params'][2] = [
                    'entry_id' => $aParams['entry_id'],
                    'node_id' => $aParams['node_id'],
                    'usage' => $aParams['usage'],
                    'order' => $aParams['order']
                ];
                $sWhereClause = "AND `tcd`.`entry_id`=:entry_id AND `tcd`.`node_id`=:node_id AND `tcd`.`usage`=:usage AND (`tcd`.`order`=(:order - 1) OR `tcd`.`order`=(:order + 1))";
                $sOrderClause = "`tcd`.`order` ASC";
                break;

            case 'user_passed':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'data_id' => $aParams['data_id']
                ];

                $sSelectClause = "`tcdu`.*";
                $sJoinClause = "INNER JOIN `" . $CNF['TABLE_CNT_DATA2USERS'] . "` AS `tcdu` ON `tcd`.`id`=`tcdu`.`data_id` AND `tcdu`.`data_id`=:data_id AND `tcdu`.`profile_id`=:profile_id";
                break;

            case 'user_track':
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'entry_id' => $aParams['entry_id'],
                    'node_id' => $aParams['node_id']
                ];

                $sJoinClause = "INNER JOIN `" . $CNF['TABLE_CNT_DATA2USERS'] . "` AS `tcdu` ON `tcd`.`id`=`tcdu`.`data_id` AND `tcdu`.`profile_id`=:profile_id";
                $sWhereClause = "AND `tcd`.`entry_id`=:entry_id AND `tcd`.`node_id`=:node_id AND `tcd`.`usage`='" . BX_COURSES_CND_USAGE_ST . "'";
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

    public function deleteContentDataWithTracks($iEntryId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `tcd`, `tcdu` USING `" . $CNF['TABLE_CNT_DATA'] . "` AS `tcd` LEFT JOIN `" . $CNF['TABLE_CNT_DATA2USERS'] . "` AS `tcdu` ON `tcd`.`id`=`tcdu`.`data_id` WHERE `tcd`.`entry_id`=:entry_id", [
            'entry_id' => $iEntryId
        ]);
    }

    public function insertContentData2Users($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return false;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_CNT_DATA2USERS'] . "` SET " . $this->arrayToSQL($aParamsSet) . " ON DUPLICATE KEY UPDATE `date`=UNIX_TIMESTAMP()") > 0 ? (int)$this->lastId() : false;
    }

    public function deleteContentData2Users($aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsWhere))
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_CNT_DATA2USERS'] . "` WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }
}

/** @} */
