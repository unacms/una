<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolDb');

class BxDolModuleQuery extends BxDolDb {
    function BxDolModuleQuery() {
    	if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::BxDolDb();
    }
    /**
     * Prevent cloning the instance
     */
    public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance() {
    	$sClass = __CLASS__;
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
    function getModuleById($iId) {
        $sSql = $this->prepare("SELECT `id`, `name`, `title`, `vendor`, `version`, `product_url`, `update_url`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `date`, `enabled` FROM `sys_modules` WHERE `id`=? LIMIT 1", $iId);
        return $this->fromMemory('sys_modules_' . $iId, 'getRow', $sSql);
    }
    function getModuleByName($sName) {
        $sSql = $this->prepare("SELECT `id`, `name`, `title`, `vendor`, `version`, `product_url`, `update_url`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `date`, `enabled` FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return $this->fromMemory('sys_modules_' . $sName, 'getRow', $sSql);
    }
    function getModuleByUri($sUri) {
        $sSql = $this->prepare("SELECT `id`, `name`, `title`, `vendor`, `version`, `product_url`, `update_url`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `date`, `enabled` FROM `sys_modules` WHERE `uri`=? LIMIT 1", $sUri);
        return $this->fromMemory('sys_modules_' . $sUri, 'getRow', $sSql);
    }
    function enableModuleByUri($sUri) {
        $sSql = $this->prepare("UPDATE `sys_modules` SET `enabled`='1' WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->query($sSql) > 0;
    }
    function disableModuleByUri($sUri) {
        $sSql = $this->prepare("UPDATE `sys_modules` SET `enabled`='0' WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->query($sSql) > 0;
    }
    function isModule($sUri) {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->getOne($sSql) > 0;
    }
    function isModuleByName($sName) {
        $sSql = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `name`=? LIMIT 1", $sName);
        return (int)$this->getOne($sSql) > 0;
    }
    function isModuleParamsUsed($sUri, $sPath, $sPrefixDb, $sPrefixClass) {
	    $sSql = "SELECT `id` FROM `sys_modules` WHERE `uri`='" . $sUri . "' || `path`='" . $sPath . "' || `db_prefix`='" . $sPrefixDb . "' || `class_prefix`='" . $sPrefixClass . "' LIMIT 1";
	    return (int)$this->getOne($sSql) > 0;
	}
    function getModules() {
        $sSql = "SELECT `id`, `type`, `name`, `title`, `vendor`, `version`, `product_url`, `update_url`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `date`, `enabled` FROM `sys_modules` ORDER BY `title`";
        return $this->fromMemory('sys_modules', 'getAll', $sSql);
    }


    function getModulesBy($aParams = array()) {
        $sPostfix = $sWhereClause = "";

        switch($aParams['type']) {
            case 'modules':
                $sPostfix .= '_modules';
                $sWhereClause .= $this->prepare(" AND `type`=?", BX_DOL_MODULE_TYPE_MODULE);
                break;
            case 'languages':
                $sPostfix .= '_languages';
                $sWhereClause .= $this->prepare(" AND `type`=?", BX_DOL_MODULE_TYPE_LANGUAGE);
                break;
            case 'templates':
                $sPostfix .= '_templates';
                $sWhereClause .= $this->prepare(" AND `type`=?", BX_DOL_MODULE_TYPE_TEMPLATE);
                break;
        }

        if(isset($aParams['active'])) {
            $sPostfix .= "_active"; 
            $sWhereClause .= $this->prepare(" AND `enabled`=?", (int)$aParams['active']);
        }

        $sSql = "SELECT 
            	`id`,
            	`type`,
            	`name`, 
            	`title`, 
            	`vendor`, 
            	`version`, 
            	`product_url`, 
            	`update_url`, 
            	`path`, 
            	`uri`, 
            	`class_prefix`, 
            	`db_prefix`, 
            	`lang_category`, 
            	`date`, 
            	`enabled` 
        	FROM `sys_modules` 
        	WHERE 1" . $sWhereClause . " 
        	ORDER BY `title`";
        return $this->fromMemory('sys_modules' . $sPostfix, 'getAll', $sSql);
    }

    function getModulesUri() {
        $sSql = "SELECT `uri` FROM `sys_modules` ORDER BY `uri`";
        return $this->fromMemory('sys_modules_uri', 'getColumn', $sSql);
    }
    function getDependent($sUri) {
        $sSql = "SELECT `id`, `title` FROM `sys_modules` WHERE `dependencies` LIKE '%" . $this->escape($sUri) . "%'";
        return $this->getAll($sSql);
    }
}
