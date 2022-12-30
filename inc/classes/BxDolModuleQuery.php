<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolModuleQuery extends BxDolDb implements iBxDolSingleton
{
    protected function __construct()
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

    function getModuleById($iId, $bFromCache = true)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `id`=? LIMIT 1", $iId);
        return $bFromCache ? $this->fromMemory('sys_modules_' . $iId, 'getRow', $sSql) : $this->getRow($sSql);
    }
    function getModuleByName($sName, $bFromCache = true)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return $bFromCache ? $this->fromMemory('sys_modules_' . $sName, 'getRow', $sSql) : $this->getRow($sSql);
    }
    function getModuleByUri($sUri, $bFromCache = true)
    {
        $sSql = $this->prepare("SELECT * FROM `sys_modules` WHERE `uri`=? LIMIT 1", $sUri);
        return $bFromCache ? $this->fromMemory('sys_modules_' . $sUri, 'getRow', $sSql) : $this->getRow($sSql);
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
        return (int)$this->fromMemory('sys_module_' . $sUri, 'getOne', $sSql) > 0;
    }
    function isModuleByName($sName)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return (int)$this->fromMemory('sys_module_' . $sName, 'getOne', $sSql) > 0;
    }
    function isModuleParamsUsed($sName, $sUri, $sPath, $sPrefixDb, $sPrefixClass)
    {
        $sSql = "SELECT `id` FROM `sys_modules` WHERE `name`=:name || `uri`=:uri || `path`=:path || `db_prefix`=:db_prefix || `class_prefix`=:class_prefix LIMIT 1";
        return (int)$this->getOne($sSql, [
            'name' => $sName,
            'uri' => $sUri,
            'path' => $sPath,
            'db_prefix' => $sPrefixDb,
            'class_prefix' => $sPrefixClass
        ]) > 0;
    }
    function isEnabled($sUri)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `uri`=? AND `enabled`='1' LIMIT 1", $sUri);
        return (int)$this->fromMemory('sys_module_enabled_' . $sUri, 'getOne', $sSql) > 0;
    }
    function isEnabledByName($sName)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `name`=? AND `enabled`='1' LIMIT 1", $sName);
        return (int)$this->fromMemory('sys_module_enabled_' . $sName, 'getOne', $sSql) > 0;
    }
    function getModules()
    {
        $sSql = "SELECT * FROM `sys_modules` ORDER BY `title`";
        return $this->fromMemory('sys_modules', 'getAll', $sSql);
    }
    function getModulesBy($aParams = array(), $bFromCache = true)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sPostfix = $sWhereClause = $sOrderByClause = "";
        $aBindings = array();

        switch($aParams['type']) {
            case 'type':
                if(!is_array($aParams['value']))
                    $aParams['value'] = array($aParams['value']);

                $sPostfix .= '_type_' . implode('_', $aParams['value']);
                $sWhereClause .= " AND `type` IN (" . $this->implode_escape($aParams['value']) . ")";
                break;

            case 'modules':
                $sPostfix .= '_modules';
                $aBindings['type'] = BX_DOL_MODULE_TYPE_MODULE;

                $sWhereClause .= " AND `type`=:type";
                break;

            case 'languages':
                $sPostfix .= '_languages';
                $aBindings['type'] = BX_DOL_MODULE_TYPE_LANGUAGE;

                $sWhereClause .= " AND `type`=:type";
                break;

            case 'templates':
                $sPostfix .= '_templates';
                $aBindings['type'] = BX_DOL_MODULE_TYPE_TEMPLATE;

                $sWhereClause .= " AND `type`=:type";
                break;

            case 'path_and_uri':
                $sPostfix .= '_path_and_uri_' . $aParams['path'] . '_' . $aParams['uri'];
            	$aMethod['name'] = 'getRow';
            	$aBindings = array_merge($aBindings, array(
                    'path' => $aParams['path'],
                    'uri' => $aParams['uri']
                ));

            	$sWhereClause .= " AND `path`=:path AND `uri`=:uri";
            	break;

            case 'all_pairs_name_uri':
                $sPostfix .= 'all_pairs_name_uri';
            	$aMethod['name'] = 'getPairs';
            	$aMethod['params'][1] = 'name';
            	$aMethod['params'][2] = 'uri';
            	break;

            case 'all_key_name':
                $sPostfix .= 'all_key_name';
            	$aMethod['name'] = 'getAllWithKey';
            	$aMethod['params'][1] = 'name';
            	break;

            case 'all':
            	break;
        }

        if(isset($aParams['active'])) {
            $sPostfix .= "_active";
            $aBindings['enabled'] = (int)$aParams['active'];

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
        $aMethod['params'][] = $aBindings;

        if(!$bFromCache || empty($sPostfix))
        	return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        return call_user_func_array(array($this, 'fromMemory'), array_merge(array('sys_modules' . $sPostfix, $aMethod['name']), $aMethod['params']));
    }

    function getModulesUri()
    {
        return $this->fromMemory('sys_modules_uri', 'getColumn', 'SELECT `uri` FROM `sys_modules` ORDER BY `uri`');
    }

    function getDependent($sName, $sUri)
    {
        $aResults = array();

        $aModules = $this->getAll("SELECT `id`, `title`, `dependencies`, `enabled` FROM `sys_modules` WHERE (`dependencies` LIKE " . $this->escape('%' . $sName . '%') . " OR `dependencies` LIKE " . $this->escape('%' . $sUri . '%') . ") AND `enabled`='1'");
        foreach($aModules as $aModule) {
            $aDependencies = explode(',', $aModule['dependencies']);
            if(in_array($sName, $aDependencies) || in_array($sUri, $aDependencies))
                $aResults[] = $aModule;
        }

        return $aResults;
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
