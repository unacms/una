<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationDb extends BxBaseModNotificationsDb
{
    protected $_oConfig;

    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }

    public function updateEvent($aParamsSet, $aParamsWhere)
    {
        return false;
    }

    public function deleteEvent($aParams, $sWhereAddon = "")
    {
        return false;
    }

    public function getEvents($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '*';
        $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = '';
        
        switch($aParams['sample']) {
            case 'stats':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'owner_id';
                $aMethod['params'][2] = 'points';
                $aMethod['params'][3] = [];
                
                $sSelectClause = '`owner_id`, SUM(`points`) AS `points`';

                if(!empty($aParams['days']))
                    $sWhereClause = $this->prepareAsString('AND `date` >= UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL ? DAY))', (int)$aParams['days']);
                
                $sGroupClause = '`owner_id`';
                $sOrderClause = '`points` DESC';
                $sLimitClause = '0, ' . (int)$aParams['limit'];
                break;
        }
        
        if($sGroupClause)
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        if($sLimitClause)
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_EVENTS'] . "` WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getLevels($aParams = []) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '`trl`.*';
        $sJoinClause = $sWhereClause = '';

        if(!empty($aParams))
            switch($aParams['sample']) {
                case 'id':
                    $aMethod['name'] = 'getRow';
                    $aMethod['params'][1] = [
                        'id' => $aParams['id']
                    ];

                    $sWhereClause = "AND `trl`.`id` = :id";
                    break;

                case 'profile_id':
                    $aMethod['params'][1] = [
                        'profile_id' => $aParams['profile_id']
                    ];

                    $sSelectClause .= ", `trpl`.`date` AS `date_assign`";
                    $sJoinClause = " INNER JOIN `" . $CNF['TABLE_PROFILES_LEVELS'] . "` AS `trpl` ON `trl`.`id`=`trpl`.`level_id` AND `trpl`.`profile_id`=:profile_id";
                    break;

                case 'points':
                    $aMethod['params'][1] = [
                        'points' => $aParams['points']
                    ];

                    $sWhereClause = "AND `trl`.`points_in` <= :points AND IF(`trl`.`points_out` <> 0, `trl`.`points_out` > :points, 1)";
                    break;
            }

        $aMethod['params'][0] = "SELECT 
                " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_LEVELS'] . "` AS `trl` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function insertProfile($iId, $iPoints)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("INSERT INTO `" . $CNF['TABLE_PROFILES'] . "` (`id`, `points`) VALUES (:id, :points) ON DUPLICATE KEY UPDATE `points`=`points`+:points", [
            'id' => $iId,
            'points' => $iPoints
        ]);
    }

    public function deleteProfile($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `" . $CNF['TABLE_PROFILES'] . "` WHERE `id`=:id", ['id' => $iId]);
    }
    
    public function getProfiles($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '`trp`.*';
        $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = '';
        
        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = "AND `trp`.`id` = :id";
                break;

            case 'stats':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'points';
                $aMethod['params'][3] = [];

                $sOrderClause = '`trp`.`points` DESC';
                $sLimitClause = '0, ' . (int)$aParams['limit'];
                break;
        }

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        if($sLimitClause)
            $sLimitClause = "LIMIT " . $sLimitClause;

        $aMethod['params'][0] = "SELECT 
                " . $sSelectClause . " 
            FROM `" . $CNF['TABLE_PROFILES'] . "` AS `trp` " . $sJoinClause . " 
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getProfilePoints($iProfileId)
    {
        $aProfile = $this->getProfiles([
            'sample' => 'id', 
            'id' => $iProfileId
        ]);

        return $aProfile && isset($aProfile['points']) ? (int)$aProfile['points'] : 0;
    }

    public function insertProfilesLevels($aSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($aSet['date']))
            $aSet['date'] = time();

        return $this->query("INSERT INTO `" . $CNF['TABLE_PROFILES_LEVELS'] . "` SET " . $this->arrayToSQL($aSet));
    }

    public function deleteProfilesLevels($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = [];
        $sWhereClause = "";

        switch($aParams['sample']) {
            case 'profile_id':
                $aBindings = [
                    'profile_id' => $aParams['profile_id']
                ];

                $sWhereClause = "`profile_id`=:profile_id";
                break;
        }

        if(!$sWhereClause)
            return false;

        return $this->query("DELETE FROM `" . $CNF['TABLE_PROFILES_LEVELS'] . "` WHERE " . $sWhereClause, $aBindings);
    }

    public function deleteProfilesLevelsByPoints($iProfileId, $iPoints)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->query("DELETE FROM `trpl` 
                USING `" . $CNF['TABLE_PROFILES_LEVELS'] . "` AS `trpl` 
                LEFT JOIN `" . $CNF['TABLE_LEVELS'] . "` AS `trl` ON `trpl`.`level_id`=`trl`.`id` 
                WHERE `trpl`.`profile_id` = :profile_id AND (`trl`.`points_in` > :points OR IF(`trl`.`points_out` <> 0, `trl`.`points_out` <= :points, 0))", [
            'profile_id' => $iProfileId,
            'points' => $iPoints
        ]);
    }

    public function getHandlers($aParams = []) 
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '*';
        $sWhereClause = '';

        if(!empty($aParams))
            switch($aParams['type']) {
                case 'alert_units_list':
                    $aMethod['name'] = 'getColumn';
                    $aMethod['params'][1] = 'alert_unit';

                    $sSelectClause = 'DISTINCT `alert_unit`';
                    break;

                default:
                    return parent::getHandlers($aParams);
            }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `{$this->_sTableHandlers}` WHERE 1 " . $sWhereClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
