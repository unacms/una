<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsQuery extends BxDolDb
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getModelsBy($aParams = [])
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
    	$sWhereClause = "";

        switch($aParams['sample']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause .= " AND `id`=:id";
                break;

            case 'name':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'name' => $aParams['name']
                );

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

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    public function getAutomatorsBy($aParams = [])
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
    	$sWhereClause = "";

        switch($aParams['sample']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause .= " AND `id`=:id";
                break;
        }

        $aMethod['params'][0] = "SELECT * 
            FROM `sys_agents_automators`
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
}

/** @} */
