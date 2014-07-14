<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolModuleQuery');

class BxDolStudioInstallerQuery extends BxDolModuleQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getConnectionsBy($aParams = array())
    {
    	$sMethod = 'getAll';
    	$sWhereClause = "";

        switch($aParams['type']) {
            case 'module':
            	$sMethod = 'getRow';
                $sWhereClause .= $this->prepare(" AND `module`=?", $aParams['value']);
                break;
        }

        $sSql = "SELECT
                `id`,
                `module`,
                `on_install`,
                `on_uninstall`,
                `on_enable`,
                `on_disable`
            FROM `sys_modules_connections`
            WHERE 1" . $sWhereClause;

        return $this->$sMethod($sSql);
    }

    function insertModule(&$aConfig)
    {
        $sProductUrl = isset($aConfig['product_url']) ? $aConfig['product_url'] : '';
        $sUpdateUrl = isset($aConfig['update_url']) ? $aConfig['update_url'] : '';

        $sDependencies = '';
        if(isset($aConfig['dependencies']) && is_array($aConfig['dependencies']))
            $sDependencies = implode(',', $aConfig['dependencies']);

        $sQuery = $this->prepare("INSERT IGNORE INTO `sys_modules`(`type`, `name`, `title`, `vendor`, `version`, `product_url`, `update_url`, `path`, `uri`, `class_prefix`, `db_prefix`, `lang_category`, `dependencies`, `date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UNIX_TIMESTAMP())", $aConfig['type'], $aConfig['name'], $aConfig['title'], $aConfig['vendor'], $aConfig['version'], $sProductUrl, $sUpdateUrl, $aConfig['home_dir'], $aConfig['home_uri'], $aConfig['class_prefix'], $aConfig['db_prefix'], $aConfig['language_category'], $sDependencies);
        $iResult = (int)$this->query($sQuery);

        return $iResult > 0 ? (int)$this->lastId() : 0;
    }

    function insertModuleTrack($iModuleId, &$aFile)
    {
        $sQuery = $this->prepare("INSERT IGNORE INTO `sys_modules_file_tracks`(`module_id`, `file`, `hash`) VALUES(?, ?, ?)", $iModuleId, $aFile['file'], $aFile['hash']);
        $this->query($sQuery);
    }

    function deleteModule($aConfig)
    {
        $sQuery = $this->prepare("SELECT `id` FROM `sys_modules` WHERE `vendor`=? AND `path`=? LIMIT 1", $aConfig['vendor'], $aConfig['home_dir']);
        $iId = (int)$this->getOne($sQuery);

        $sQuery = $this->prepare("DELETE FROM `sys_modules` WHERE `vendor`=? AND `path`=? LIMIT 1", $aConfig['vendor'], $aConfig['home_dir']);
        $this->query($sQuery);

        $sQuery = $this->prepare("DELETE FROM `sys_modules_file_tracks` WHERE `module_id`=?", $iId);
        $this->query($sQuery);

        return $iId;
    }
}

/** @} */
