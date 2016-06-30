<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolModuleQuery extends BxDolDb implements iBxDolSingleton
{
    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
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

    function getModuleById($iId)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `id`=? LIMIT 1", $iId);
        return $this->fromMemory('sys_modules_' . $iId, 'getRow', $sSql);
    }
    function getModuleByName($sName)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return $this->fromMemory('sys_modules_' . $sName, 'getRow', $sSql);
    }
    function getModuleByUri($sUri)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `uri`=? LIMIT 1", $sUri);
        return $this->fromMemory('sys_modules_' . $sUri, 'getRow', $sSql);
    }
    function enableModuleByUri($sUri)
    {
        $sSql = $this->prepare("UPDATE `sys_modules` SET `enabled`='1' WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->query($sSql) > 0;
    }
    function disableModuleByUri($sUri)
    {
        $sSql = $this->prepare("UPDATE `sys_modules` SET `enabled`='0' WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->query($sSql) > 0;
    }
    function setModulePendingUninstall($sUri, $bPendingUninstall)
    {
        $sSql = $this->prepare("UPDATE `sys_modules` SET `pending_uninstall` = ? WHERE `uri` = ? LIMIT 1", $bPendingUninstall ? 1 : 0, $sUri);
        return $this->query($sSql);
    }
    function isModule($sUri)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->getOne($sSql) > 0;
    }
    function isModuleByName($sName)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return (int)$this->getOne($sSql) > 0;
    }
    function isModuleParamsUsed($sUri, $sPath, $sPrefixDb, $sPrefixClass)
    {
        $sSql = "SELECT `id` FROM `sys_modules` WHERE `uri`='" . $sUri . "' || `path`='" . $sPath . "' || `db_prefix`='" . $sPrefixDb . "' || `class_prefix`='" . $sPrefixClass . "' LIMIT 1";
        return (int)$this->getOne($sSql) > 0;
    }
    function isEnabled($sUri)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `uri`=? AND `enabled`='1' LIMIT 1", $sUri);
        return (int)$this->getOne($sSql) > 0;
    }
    function isEnabledByName($sName)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `name`=? AND `enabled`='1' LIMIT 1", $sName);
        return (int)$this->getOne($sSql) > 0;
    }
    function getModules()
    {
        $sSql = "SELECT * FROM `sys_modules` ORDER BY `title`";
        return $this->fromMemory('sys_modules', 'getAll', $sSql);
    }
    function getModulesBy($aParams = array())
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sPostfix = $sWhereClause = $sOrderByClause = "";

        switch($aParams['type']) {
            case 'modules':
                $sPostfix .= '_modules';
                $aMethod['params'][1] = array(
                	'type' => BX_DOL_MODULE_TYPE_MODULE
                );

                $sWhereClause .= " AND `type`=:type";
                break;

            case 'languages':
                $sPostfix .= '_languages';
                $aMethod['params'][1] = array(
                	'type' => BX_DOL_MODULE_TYPE_LANGUAGE
                );

                $sWhereClause .= " AND `type`=:type";
                break;

            case 'templates':
                $sPostfix .= '_templates';
                $aMethod['params'][1] = array(
                	'type' => BX_DOL_MODULE_TYPE_TEMPLATE
                );

                $sWhereClause .= " AND `type`=?";
                break;

            case 'path_and_uri':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                	'path' => $aParams['path'],
            		'uri' => $aParams['uri']
                );

            	$sWhereClause .= " AND `path`=:path AND `uri`=:uri";
            	break;

            case 'all':
            	break;
        }

        if(isset($aParams['active'])) {
            $sPostfix .= "_active";
            $aMethod['params'][1]['enabled'] = (int)$aParams['active'];

            $sWhereClause .= " AND `enabled`=:enabled";
        }

        $sOrderByClause = " ORDER BY " . (isset($aParams['order_by']) ? $aParams['order_by'] : '`title`');

        $aMethod['params'][0] = "SELECT
                `id`,
                `type`,
                `name`,
                `title`,
                `vendor`,
                `version`,
                `help_url`,
                `path`,
                `uri`,
                `class_prefix`,
                `db_prefix`,
                `lang_category`,
                `date`,
                `enabled`
            FROM `sys_modules`
            WHERE 1 " . $sWhereClause . $sOrderByClause;

        if(empty($sPostfix))
        	return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        return call_user_func_array(array($this, 'fromMemory'), array_merge(array('sys_modules' . $sPostfix, $aMethod['name']), $aMethod['params']));
    }

    function getModulesUri()
    {
        $sSql = "SELECT `uri` FROM `sys_modules` ORDER BY `uri`";
        return $this->fromMemory('sys_modules_uri', 'getColumn', $sSql);
    }

    function getDependent($sUri)
    {
        $sSql = "SELECT `id`, `title` FROM `sys_modules` WHERE `dependencies` LIKE " . $this->escape('%' . $sUri . '%');
        return $this->getAll($sSql);
    }

	public function updateModule($aParamsSet, $aParamsWhere = array())
    {
        if(empty($aParamsSet))
            return false;

		$sWhereClause = !empty($aParamsWhere) ? $this->arrayToSQL($aParamsWhere, " AND ") : "1";

        $sSql = "UPDATE `sys_modules` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $sWhereClause;
        return $this->query($sSql);
    }
}

/** @} */
