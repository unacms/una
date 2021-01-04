<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioRolesQuery extends BxDolDb implements iBxDolSingleton
{
    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioRolesQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function setRole ($iAccountId, $iRole)
    {
        $sBindings = array(
            'account_id' => $iAccountId,
            'role' => $iRole
        );
        if(!$this->query("REPLACE INTO `sys_std_roles_members` SET `account_id`=:account_id, `role`=:role", $sBindings))
            return false;

        $this->query("UPDATE `sys_accounts` SET `role`=:role WHERE `id`=:id", array(
            'id' => $iAccountId,
            'role' => $iRole == 0 ? 1 : 3
        ));

        return true;
    }

    public function getRoles($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tr`.`Order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause .= "AND `tr`.`id`=:id";
                $sLimitClause .= "LIMIT 1";
                break;               

            case 'all_active':
                $sWhereClause .= "AND `tr`.`active`=1";
                break;

            case 'all_active_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                $sWhereClause .= "AND `tr`.`active`=1";
                break;

            case 'all_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'title';
                break;

            case 'all_order_id':
                $sOrderClause = "ORDER BY `tr`.`id` ASC";
                break;

            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT 
                `tr`.`id` AS `id`,
                `tr`.`name` AS `name`,
                `tr`.`title` AS `title`,
                `tr`.`description` AS `description`,
                `tr`.`active` AS `active`,
                `tr`.`order` AS `order`" . $sSelectClause . "
            FROM `sys_std_roles` AS `tr` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function isRoleUsed($iId)
    {
        return (int)$this->getOne("SELECT `id` FROM `sys_std_roles_members` WHERE `role` & :role_id <> 0", array(
            'role_id' => $iId
        )) > 0;
    }

    public function getRoleOrderMax()
    {
        return (int)$this->getOne("SELECT MAX(`order`) FROM `sys_std_roles` WHERE 1");
    }

    public function updateRoles($aSet, $aWhere)
    {
        if(empty($aSet) || empty($aWhere))
            return false;

        return (int)$this->query("UPDATE `sys_std_roles` SET " . $this->arrayToSQL($aSet) . " WHERE " . $this->arrayToSQL($aWhere)) > 0;
    }

    public function deleteRoles($aParams)
    {
    	$aBindings = array();
        $sWhereClause = $sLimitClause = "";

        switch($aParams['type']) {
            case 'by_id':
            	$aBindings = array(
                    'id' => $aParams['id']
                );

                $sWhereClause .= "AND `id`=:id";
                break;
        }

        $sSql = "DELETE FROM `sys_std_roles` WHERE 1 " . $sWhereClause . " " . $sLimitClause;

        return (int)$this->query($sSql, $aBindings) > 0;
    }

    public function getActions($aParams)
    {
        $sMemoryKey = '';
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tra`.`name` ASC";
           
        switch($aParams['type']) {
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'name' => $aParams['name'],
                );

                $sWhereClause .= "AND `tra`.`name`=:name ";
                break;

            case 'by_names':
            	$aMethod['params'][1] = array();

                $sWhereClause .= "AND `tra`.`name` IN(" . $this->implode_escape($aParams['value']) . ") ";
                $sMemoryKey = 'BxDolStudioRolesQuery::getActions' . $aParams['type'] . $sWhereClause;
                break;

            case 'by_role_id':
            	$aMethod['params'][1] = array(
                    'role_id' => $aParams['role_id']
                );

                $sJoinClause .= "LEFT JOIN `sys_std_roles_actions2roles` AS `trar` ON `tra`.`id`=`trar`.`action_id` ";
                $sWhereClause .= "AND `trar`.`role_id`=:role_id ";
                break;

            case 'by_role_id_key_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = array(
                    'role_id' => $aParams['role_id']
                );

                $sJoinClause .= "LEFT JOIN `sys_std_roles_actions2roles` AS `trar` ON `tra`.`id`=`trar`.`action_id` ";
                $sWhereClause .= "AND `trar`.`role_id`=:role_id";
                break;

            case 'counter_by_roles':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'role_id';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", `trar`.`role_id` AS `role_id`, COUNT(`trar`.`action_id`) AS `counter`";
                $sJoinClause = "LEFT JOIN `sys_std_roles_actions2roles` AS `trar` ON `tra`.`id`=`trar`.`action_id` ";
                $sGroupClause = "GROUP BY `trar`.`role_id`";
                break;
        }

        $aMethod['params'][0] = "SELECT 
                `tra`.`id` AS `id`,
                `tra`.`name` AS `name`,
                `tra`.`title` AS `title`,
                `tra`.`description` AS `description`" . $sSelectClause . "
            FROM `sys_std_roles_actions` AS `tra` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        if ($sMemoryKey) {
            array_unshift($aMethod['params'], $sMemoryKey, $aMethod['name']);
            return call_user_func_array(array($this, 'fromMemory'), $aMethod['params']);
        }

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function switchAction($iRoleId, $iActionId, $bEnable)
    {
        $aBindings = array(
            'role_id' => $iRoleId,
            'action_id' => $iActionId
        );

        if($bEnable)
            $sSql = "INSERT INTO `sys_std_roles_actions2roles` SET `role_id`=:role_id, `action_id`=:action_id";
        else
            $sSql = "DELETE FROM `sys_std_roles_actions2roles` WHERE `role_id`=:role_id AND `action_id`=:action_id";

        return (int)$this->query($sSql, $aBindings) > 0;
    }

    public function isActionAllowed($iRole, $iActionId)
    {
        $aResult = $this->getRow("SELECT * FROM `sys_std_roles_actions2roles` WHERE :role & POW(2, (`role_id` - 1)) != 0 AND `action_id`=:action_id", array(
            'role' => $iRole,
            'action_id' => $iActionId
        ));

        return !empty($aResult) && is_array($aResult);
    }

    public function getMembers($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `trm`.`id` ASC";

        switch($aParams['type']) {
            case 'by_account_id':
                $aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'account_id' => $aParams['account_id']
                );

                $sWhereClause .= "AND `trm`.`account_id`=:account_id ";
                break;

            case 'by_role_id':
            	$aMethod['params'][1] = array(
                    'role_id' => $aParams['role_id']
                );

                $sWhereClause .= "AND `trm`.`role_id`=:role_id ";
                break;
        }

        $aMethod['params'][0] = "SELECT 
                `trm`.`id` AS `id`,
                `trm`.`account_id` AS `account_id`,
                `trm`.`role` AS `role`" . $sSelectClause . "
            FROM `sys_std_roles_members` AS `trm` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
