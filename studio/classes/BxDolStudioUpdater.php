<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioInstaller');

class BxDolStudioUpdater extends BxDolStudioInstaller {
    function BxDolStudioUpdater($aConfig) {
        parent::BxDolStudioInstaller($aConfig);
        $this->_sModulePath = $this->_sBasePath . $aConfig['module_dir'];

        $this->_aActions = array_merge($this->_aActions, array(
            'check_module_exists' => array(
                'title' => _t('_adm_txt_modules_check_module_exists'),
            ),
            'check_module_version' => array(
                'title' => _t('_adm_txt_modules_check_module_version'),
            ),
            'check_module_hash' => array(
                'title' => _t('_adm_txt_modules_check_module_hash'),
            ),
            'update_files' => array(
                'title' => _t('_adm_txt_modules_update_files'),
            ),
        ));
    }

    function update($aParams) {
        $oDb = BxDolDb::getInstance();

        $aResult = array(
            'operation_title' => _t('_adm_txt_modules_operation_update', $this->_aConfig['title'], $this->_aConfig['version_from'], $this->_aConfig['version_to'])
        );

        //--- Check for module to update ---//
        $sQuery = $oDb->prepare("SELECT `id`, `version` FROM `sys_modules` WHERE `path`=? AND `uri`=? LIMIT 1", $this->_aConfig['module_dir'], $this->_aConfig['module_uri']);
        $aModuleInfo = $oDb->getRow($sQuery);
        if(!$aModuleInfo)
            return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_exists', false, '_adm_err_modules_module_not_found'),
                'result' => false
            ));

        //--- Check version ---//
        if($aModuleInfo['version'] != $this->_aConfig['version_from'])
            return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_version', false, '_adm_err_modules_wrong_version'),
                'result' => false
            ));

        //--- Check hash ---//
        $sQuery = $oDb->prepare("SELECT `file`, `hash` FROM `sys_modules_file_tracks` WHERE `module_id`=?", $aModuleInfo['id']);
        $aFilesOrig = $oDb->getAllWithKey($sQuery, "file");

        $aFiles = array();
        $this->_hash($this->_sModulePath, $aFiles);
        foreach($aFiles as $aFile)
            if(!isset($aFilesOrig[$aFile['file']]) || $aFilesOrig[$aFile['file']]['hash'] != $aFile['hash'])
                return array_merge($aResult, array(
                    'message' => $this->_displayResult('check_module_hash', false, '_adm_err_modules_module_was_modified'),
                    'result' => false
                ));

        //--- Perform action and check results ---//
        $aResult = array_merge($aResult, $this->_perform('install', 'Update'));
        if($aResult['result']) {
            $sQuery = $oDb->prepare("UPDATE `sys_modules` SET `version`=? WHERE `id`=?", $this->_aConfig['version_to'], $aModuleInfo['id']);
            $oDb->query($sQuery);
            $sQuery = $oDb->prepare("DELETE FROM `sys_modules_file_tracks` WHERE `module_id`=?", $aModuleInfo['id']);
            $oDb->query($sQuery);

            $aFiles = array();
            $this->_hash(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['home_dir'], $aFiles);
            foreach($aFiles as $aFile) {
                $sQuery = $oDb->prepare("INSERT IGNORE INTO `sys_modules_file_tracks`(`module_id`, `file`, `hash`) VALUES(?, ?, ?)", $aModuleInfo['id'], $aFile['file'], $aFile['hash']);
                $oDb->query($sQuery);
            }
        }

        return $aResult;
    }

    //--- Action Methods ---//
    function actionUpdateFiles($bInstall = true) {
        $sPath = $this->_sHomePath . 'source/';
        if(!file_exists($sPath))
            return BX_DOL_INSTALLER_FAILED;

        $oFtp = new BxDolFtp($_SERVER['HTTP_HOST'], getParam('sys_ftp_login'), getParam('sys_ftp_password'), getParam('sys_ftp_dir'));
        if($oFtp->connect() == false)
            return BX_DOL_INSTALLER_FAILED;

        return $oFtp->copy($sPath . '*', 'modules/' . $this->_aConfig['module_dir']) ? BX_DOL_INSTALLER_SUCCESS : BX_DOL_INSTALLER_FAILED;
    }

    function actionUpdateLanguages($bInstall = true) {
        $oDb = BxDolDb::getInstance();

        $aLanguages = $oDb->getAll("SELECT `ID` AS `id`, `Name` AS `name`, `Title` AS `title` FROM `sys_localization_languages`");

        //--- Process languages' key=>value pears ---//
        $sModuleConfig = $this->_sHomePath .'install/config.php';
        if(!file_exists($sModuleConfig))
            return array('code' => BX_DOL_INSTALLER_FAILED, 'content' => '_adm_err_modules_module_config_not_found');

        include($sModuleConfig);
        $sQuery = $oDb->prepare("SELECT `ID` FROM `sys_localization_categories` WHERE `Name`=? LIMIT 1", $aConfig['language_category']);
        $iCategoryId = (int)$oDb->getOne($sQuery);

        foreach($aLanguages as $aLanguage)
            $this->_updateLanguage($bInstall, $aLanguage, $iCategoryId);

        //--- Recompile all language files ---//
        $aResult = array();
        foreach($aLanguages as $aLanguage) {
            $bResult = compileLanguage($aLanguage['id']);

            if(!$bResult)
                $aResult[] = $aLanguage['title'];
        }
        return empty($aResult) ? BX_DOL_INSTALLER_SUCCESS : array('code' => BX_DOL_INSTALLER_FAILED, 'content' => $aResult);
    }

    //--- Protected methods ---//

    function _updateLanguage($bInstall, $aLanguage, $iCategoryId = 0) {
        $oDb = BxDolDb::getInstance();

        $sPath = $this->_sHomePath . 'install/langs/' . $aLanguage['name'] . '.php';
        if(!file_exists($sPath)) return false;

        include($sPath);

        //--- Process delete ---//
        if (isset($aLangContentDelete) && is_array($aLangContentDelete)) {
            foreach ($aLangContentDelete as $sKey) {
                $sQuery = $oDb->prepare("DELETE FROM `sys_localization_keys`, `sys_localization_strings` USING `sys_localization_keys`, `sys_localization_strings` WHERE `sys_localization_keys`.`ID`=`sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key`=? AND `sys_localization_strings`.`IDLanguage`=?", $sKey, $aLanguage['id']);
                $oDb->query($sQuery);
            }
        }

        //--- Process add ---//
        if (isset($aLangContentAdd) && is_array($aLangContentAdd)) {
            foreach ($aLangContentAdd as $sKey => $sValue) {
                $sQuery = $oDb->prepare("INSERT IGNORE INTO `sys_localization_keys`(`IDCategory`, `Key`) VALUES(?, ?)", $iCategoryId, $sKey);
                $mixedResult = $oDb->query($sQuery);
                if($mixedResult === false || $mixedResult <= 0)
                    continue;

                $iLangKeyId = (int)$oDb->lastId();
                $sQuery = $oDb->prepare("INSERT INTO `sys_localization_strings`(`IDKey`, `IDLanguage`, `String`) VALUES(?, ?, ?)", $iLangKeyId, $aLanguage['id'], $sValue);
                $oDb->query($sQuery);
            }
        }

        //--- Process Update ---//
        if (isset($aLangContentUpdate) && is_array($aLangContentUpdate)) {
            foreach ($aLangContentUpdate as $sKey => $sValue) {
                $sQuery = $oDb->prepare("SELECT `ID` FROM `sys_localization_keys` WHERE `Key`=?", $sKey);
                $iLangKeyId = (int)$MySQL->getOne($sQuery);
                if($iLangKeyId == 0)
                    continue;

                $sQuery = $oDb->prepare("UPDATE `sys_localization_strings` SET `String`=? WHERE `IDKey`=? AND `IDLanguage`=?", $sValue, $iLangKeyId, $aLanguage['id']);
                $oDb->query($sQuery);
            }
        }

        return true;
    }
}
/** @} */