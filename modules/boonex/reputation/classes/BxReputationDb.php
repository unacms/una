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

    public function insertEvent($aParamsSet)
    {
        return 0;
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
        return [];
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
    
    public function getProfile($aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = '*';
        $sWhereClause = '';
        
        switch($aParams['sample']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause = "AND `id` = :id";
                break;
        }
        
        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_PROFILES'] . "` WHERE 1 " . $sWhereClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
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
