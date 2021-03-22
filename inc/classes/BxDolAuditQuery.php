<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolCategories
 */
class BxDolAuditQuery extends BxDolDb implements iBxDolSingleton
{
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
        $this->_sTable = 'sys_audit';
    }
    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        $sClass = __CLASS__;
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function getData($aParams = array(), &$aItems = false)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`sc`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = $sLimitClause = "";
        $sOrderClause = "`sc`.`added` ASC";

        if(isset($aParams['count_only']) && $aParams['count_only'] === true) {
            $aMethod['name'] = 'getOne';
            $sSelectClause = "COUNT(`sc`.`id`)";
        }

        switch($aParams['type']) {
            case 'profile_list':
                $aMethod['name'] = 'getColumn';
                $sSelectClause = " DISTINCT `sc`.`profile_id`";
               
                break;
                
            case 'action_list':
                $aMethod['name'] = 'getColumn';
                $sSelectClause = " DISTINCT `sc`.`action_lang_key`";
                
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $this->_sTable . "` AS `sc`" . $sJoinClause . " 
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause. " " . $sLimitClause;
        $oRv = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
        if ($aItems === false)
            return $oRv;
        $aItems = $oRv;   
    }
}

/** @} */
