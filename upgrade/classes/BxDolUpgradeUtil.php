<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

class BxDolUpgradeUtil
{
    var $oDb;
    var $sFolder;

    function BxDolUpgradeUtil($oDb)
    {
        $this->oDb = $oDb;
    }

    function executeCheck ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';

        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'check.php';
        if (!file_exists($sFile))
            return $sModule ? true : 'Check script was not found: ' . $sFile;

        return include ($sFile);
    }

    function executeConclusion ($sModule = '')
    {
        if (!$this->sFolder)
            return '';

        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'conclusion.html';
        if (!file_exists($sFile))
            return '';

        return file_get_contents ($sFile);
    }

    function isExecuteScriptAvail ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';
        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'script.php';
        return file_exists($sFile) ? true : false;
    }

    function executeScript ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';

        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'script.php';
        if (!file_exists($sFile))
            return true; // if custom script was not found just skip it

        return include ($sFile);
    }

    function isExecuteSQLAvail ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';
        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'sql.sql';
        return file_exists($sFile) ? true : false;
    }

    function executeSQL ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';

        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'sql.sql';
        if (!file_exists($sFile))
            return true; // if sql script was not found just skip it

        $aReplace = array ();
        if ($sModule) {
            $aModule = $this->oDb->getRow ("SELECT * FROM `sys_modules` WHERE `uri` = '$sModule' LIMIT 1");
            if (!$aModule)
                return true; // it looks like module is not installed - skip it
            $aReplace = array (
                'from' => array ('[db_prefix]'),
                'to'   => array ($aModule['db_prefix']),
            );
        }

        $mixedResult = $this->oDb->executeSQL ($sFile, $aReplace);

        if (true === $mixedResult)
            return true;

        if (!is_array($mixedResult)) // it looks like string error, return it
            return $mixedResult;

        $s = '';
        foreach ($mixedResult as $a)
            $s .= "<b>{$a['query']}</b>: {$a['error']} <br />";
        return $s;
    }

    function executeLangsAdd ($sModule = '')
    {
        if (!$this->sFolder)
            return 'Upgrade path folder is not defined';

        $aLangs = $this->readLangs ($sModule);
        foreach ($aLangs as $sLang) {
            $this->_executeLangAdd($sModule, $sLang);
        }

        return true;
    }

    function _executeLangAdd ($sModule, $sLang = 'en')
    {
        $sFile = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '') . 'lang_' . $sLang . '.php';
        if (!file_exists($sFile))
            return true; // just skip if language file is not found

        include ($sFile);
        if (!$aLangContent && !is_array($aLangContent))
            return true;

        $iLanguageId = (int)$this->oDb->getOne("SELECT `ID` FROM `sys_localization_languages` WHERE `Name`='$sLang' LIMIT 1");
        if (!$iLanguageId)
            return true; // just skip the language if it is not available

        if ($sModule) {
            $aModule = $this->oDb->getRow ("SELECT * FROM `sys_modules` WHERE `uri` = '$sModule' LIMIT 1");
            if (!$aModule)
                return true; // it looks like module is not installed - skip it

            $sModuleConfigFile = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'install/config.php';
            require ($sModuleConfigFile);
            $iCategoryId = $this->oDb->getOne ("SELECT `ID` FROM `sys_localization_categories` WHERE `Name`='" . $aConfig['language_category'] . "' LIMIT 1");
            if (!$iCategoryId && $aConfig['language_category']) {
                if ($this->oDb->query ("INSERT INTO `sys_localization_categories` SET `Name`='" . $aConfig['language_category'] . "'"))
                    $iCategoryId = $this->oDb->lastId();
                else
                    return "Can not determine or create language category ID";
            }
        } else {
            $iCategoryId = 1;
        }

        foreach ($aLangContent as $sKey => $sValue) {
            $aLangKey = $this->oDb->getRow("SELECT `ID`, `IDCategory` FROM `sys_localization_keys` WHERE `Key`='" . $this->oDb->escape($sKey) . "' LIMIT 1");
            $iLangKeyId = isset($aLangKey['ID']) && (int)$aLangKey['ID'] ? (int)$aLangKey['ID'] : false;
            if (!$iLangKeyId) {
                if (!$this->oDb->query("INSERT INTO `sys_localization_keys`(`IDCategory`, `Key`) VALUES('$iCategoryId', '" . $this->oDb->escape($sKey) . "')"))
                    continue;
                $iLangKeyId = $this->oDb->lastId();
            } else {
                $iLangKeyCat = isset($aLangKey['IDCategory']) && (int)$aLangKey['IDCategory'] ? (int)$aLangKey['IDCategory'] : 0;
                if ($iLangKeyCat != $iCategoryId)
                    $this->oDb->query("UPDATE `sys_localization_keys` SET `IDCategory` = '$iCategoryId' WHERE `Key` = '" . $this->oDb->escape($sKey) . "' LIMIT 1");
            }
            $this->oDb->res("DELETE FROM `sys_localization_strings` WHERE `IDKey` = '$iLangKeyId' AND `IDLanguage` = '$iLanguageId'");
            $this->oDb->res("INSERT INTO `sys_localization_strings`(`IDKey`, `IDLanguage`, `String`) VALUES('$iLangKeyId', '$iLanguageId', '" . $this->oDb->escape($sValue) . "')");
        }

        return true;
    }

    function readLangs ($sModule = '')
    {
        $sDir = BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/' . ($sModule ? 'modules/' . $sModule . '/' : '');

        if (!($h = opendir($sDir)))
            return false;

        $aRet = array();
        while (false !== ($sFile = readdir($h))) {
            if ('.' == $sFile || '..' == $sFile || '.' == $sFile[0] || !is_file($sDir . '/' . $sFile) || !preg_match('/^lang_([a-z]+)\.php$/', $sFile, $m))
                continue;
            $sLang = $m[1];
            if ($sLang != $this->oDb->getOne("SELECT `Name` FROM `sys_localization_languages` WHERE `Name` = '$sLang' LIMIT 1"))
                continue;
            $aRet[] = $sLang;
        }

        closedir($h);

        return $aRet;

    }

    function readModules ()
    {
        if (!$this->sFolder)
            return false;

        if (!file_exists(BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/modules/'))
            return false;

        if (!($h = opendir(BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/modules/')))
            return false;

        $aRet = array();
        while (false !== ($sModule = readdir($h))) {
            if ('.' == $sModule || '..' == $sModule || '.' == $sModule[0] || !is_dir(BX_UPGRADE_DIR_UPGRADES . $this->sFolder . '/modules/' . $sModule))
                continue;
            if ($sModule != $this->oDb->getOne("SELECT `uri` FROM `sys_modules` WHERE `uri` = '$sModule' LIMIT 1"))
                continue;
            $aRet[] = $sModule;
        }

        closedir($h);

        return $aRet;
    }

    function checkFolder ($sFolder = '')
    {
        if (!$sFolder)
            $sFolder = $this->sFolder;
        $sFullPath = BX_UPGRADE_DIR_UPGRADES . $sFolder . '/';

        if (!preg_match('/^[A-Za-z0-9\.\-]+$/', $sFolder) || !file_exists($sFullPath))
            return 'Upgrade path was not found';

        return true;
    }

    function setFolder ($sFolder)
    {
        $this->sFolder = $sFolder;
    }

    function readUpgrades ()
    {
        if (!($h = opendir(BX_UPGRADE_DIR_UPGRADES))) {
            return false;
        }

        $aRet = array();
        while (false !== ($sFolder = readdir($h))) {
            if ('.' == $sFolder || '..' == $sFolder || !is_dir(BX_UPGRADE_DIR_UPGRADES . $sFolder) || !$this->checkFolder($sFolder))
                continue;
            $aRet[] = $sFolder;
        }

        closedir($h);

        return $aRet;
    }
}

/** @} */
