<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolAIQuery extends BxDolDb
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelsBy($aParams = [])
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
    	$sWhereClause = "";

        switch($aParams['sample']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause .= " AND `id`=:id";
                break;

            case 'name':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'name' => $aParams['name']
                ];

                $sWhereClause .= " AND `name`=:name";
                break;

            case 'all_pairs':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                break;
        }

        $aMethod['params'][0] = "SELECT * 
            FROM `sys_agents_models`
            WHERE 1" . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getAutomatorsBy($aParams = [])
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];
        $sSelectClause = "`taa`.*";
    	$sJoinClause = $sWhereClause = "";

        switch($aParams['sample']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sWhereClause .= " AND `taa`.`id`=:id";
                break;

            case 'id_full':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = [
                    'id' => $aParams['id']
                ];

                $sSelectClause .= ", `tam`.`name` AS `model_name`, `tam`.`url` AS `model_url`, `tam`.`key` AS `model_key`, `tam`.`model` AS `model_model`, `tam`.`params` AS `model_params`";
                $sJoinClause .= " LEFT JOIN `sys_agents_models` AS `tam` ON `taa`.`model_id`=`tam`.`id`";
                $sWhereClause .= " AND `taa`.`id`=:id";
                break;
            
            case 'events':
                $aMethod['params'][1] = [
                    'type' => BX_DOL_AI_AUTOMATOR_EVENT,
                    'alert_unit' => $aParams['alert_unit'],
                    'alert_action' => $aParams['alert_action']
                ];

                $sWhereClause .= " AND `taa`.`type`=:type AND `taa`.`alert_unit`=:alert_unit AND `taa`.`alert_action`=:alert_action";

                if(isset($aParams['active'])) {
                    $aMethod['params'][1]['active'] = (int)$aParams['active'];

                    $sWhereClause .= " AND `taa`.`active`=:active";
                }
                break;
                
            case 'schedulers':
                $aMethod['params'][1] = [
                    'type' => BX_DOL_AI_AUTOMATOR_SCHEDULER,
                ];

                $sWhereClause .= " AND `taa`.`type`=:type";

                if(isset($aParams['active'])) {
                    $aMethod['params'][1]['active'] = (int)$aParams['active'];

                    $sWhereClause .= " AND `taa`.`active`=:active";
                }
                break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . "
            FROM `sys_agents_automators` AS `taa` " . $sJoinClause . "
            WHERE 1" . $sWhereClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function updateAutomators($aSetClause, $aWhereClause)
    {
        if(empty($aSetClause) || empty($aWhereClause))
            return false;

        return (int)$this->query("UPDATE `sys_agents_automators` SET " . $this->arrayToSQL($aSetClause) . " WHERE " . $this->arrayToSQL($aWhereClause)) > 0;
    }
    
}

/** @} */
