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
    public function __construct($aConfig) {
        parent::__construct($aConfig);
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

    public function update($aParams) {
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
            $this->_hash(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['module_dir'], $aFiles);
            foreach($aFiles as $aFile) {
                $sQuery = $oDb->prepare("INSERT IGNORE INTO `sys_modules_file_tracks`(`module_id`, `file`, `hash`) VALUES(?, ?, ?)", $aModuleInfo['id'], $aFile['file'], $aFile['hash']);
                $oDb->query($sQuery);
            }

            //--- Remove update pckage ---//
            $this->delete();
        }

        return $aResult;
    }

    //--- Action Methods ---//
    public function actionUpdateFiles($bInstall = true) {
        $sPath = $this->_sHomePath . 'source/';
        if(!file_exists($sPath))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

		bx_import('BxDolFtp');
        $oFtp = new BxDolFtp($_SERVER['HTTP_HOST'], getParam('sys_ftp_login'), getParam('sys_ftp_password'), getParam('sys_ftp_dir'));
        if($oFtp->connect() == false)
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        return $oFtp->copy($sPath . '*', 'modules/' . $this->_aConfig['module_dir']) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    public function actionUpdateLanguages($bInstall = true) {
        $oDb = BxDolDb::getInstance();

        bx_import('BxDolStudioLanguagesUtils');
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguages->getLanguages();

        //--- Process languages' key=>value pears ---//
        $sModuleConfig = $this->_sHomePath . 'install/config.php';
        if(!file_exists($sModuleConfig))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        include($sModuleConfig);
        $iCategoryId = $oLanguages->getLanguageCategory($aConfig['language_category']);

        foreach($aLanguages as $sName => $sTitle)
            $this->_updateLanguage($bInstall, $sName, $iCategoryId);

        return $oLanguages->compileLanguage(0, true) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    protected function _updateLanguage($bInstall, $sLanguage, $iCategoryId = 0) {
        $oDb = BxDolDb::getInstance();
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $sPath = $this->_sHomePath . 'install/langs/' . $sLanguage . '.xml';
        $aLanguageInfo = $oLanguages->readLanguage($sPath, 'update');
        if(empty($aLanguageInfo))
        	return false;

		$iLanguage = $oLanguages->getLangId($sLanguage);

        //--- Process delete ---//
        if(isset($aLanguageInfo['strings_del']) && is_array($aLanguageInfo['strings_del']))
			$oLanguages->deleteLanguageKeys($aLanguageInfo['strings_del']);

        //--- Process add ---//
        if(isset($aLanguageInfo['strings_add']) && is_array($aLanguageInfo['strings_add']))
			$oLanguages->addLanguageKeys($iLanguage, $iCategoryId, $aLanguageInfo['strings_add']);

        //--- Process update ---//
        if(isset($aLanguageInfo['strings_upd']) && is_array($aLanguageInfo['strings_upd'])) {
        	$oLanguages->deleteLanguageKeys($aLanguageInfo['strings_upd']);
        	$oLanguages->addLanguageKeys($iLanguage, $iCategoryId, $aLanguageInfo['strings_upd']);
        }

        return true;
    }
}
/** @} */
