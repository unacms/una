<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolStudioInstaller');

class BxDolStudioUpdater extends BxDolStudioInstaller
{
    public function __construct($aConfig)
    {
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

    public function update($aParams)
    {
        $oDb = bx_instance('BxDolStudioInstallerQuery');

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
        $aFiles = array();
        $this->hashFiles(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['module_dir'], $aFiles);

        list($aFilesChanged, $fChangedPercent) = $this->hashCheck($aFiles, $aModuleInfo['id']);
        $bAutoupdateForceModifiedFiles = getParam('sys_autoupdate_force_modified_files') == 'on';

    	if(!empty($aFilesChanged) && !$bAutoupdateForceModifiedFiles) 
    		return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_hash', false, '_adm_err_modules_module_was_modified'),
                'result' => false
            ));
		else if($fChangedPercent > BX_FORCE_AUTOUPDATE_MAX_CHANGED_FILES_PERCENT && $bAutoupdateForceModifiedFiles) 
			return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_hash', false, _t('_sys_upgrade_files_checksum_failed_too_many', round($fChangedPercent * 100))),
                'result' => false
            ));

        //--- Perform action and check results ---//
        $aResult = array_merge($aResult, $this->_perform('install', 'Update'));
        if($aResult['result']) {
            $sQuery = $oDb->prepare("UPDATE `sys_modules` SET `version`=? WHERE `id`=?", $this->_aConfig['version_to'], $aModuleInfo['id']);
            $oDb->query($sQuery);

            $oDb->deleteModuleTrackFiles($aModuleInfo['id']);

            $aFiles = array();
            $this->hashFiles(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['module_dir'], $aFiles);
            foreach($aFiles as $aFile)
                $oDb->insertModuleTrack($aModuleInfo['id'], $aFile);

            //--- Remove update pckage ---//
            $this->delete();
        }

        return $aResult;
    }

    //--- Action Methods ---//
    public function actionUpdateFiles($bInstall = true)
    {
        $sPath = $this->_sHomePath . 'source/';
        if(!file_exists($sPath))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

		$oFile = $this->_getFileManager();
		if(empty($oFile))
			return BX_DOL_STUDIO_INSTALLER_FAILED;

        return $oFile->copy($sPath . '*', 'modules/' . $this->_aConfig['module_dir']) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    public function actionUpdateLanguages($bInstall = true)
    {
        $oDb = BxDolDb::getInstance();

        bx_import('BxDolStudioLanguagesUtils');
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguages->getLanguages();

        //--- Process languages' key=>value pears ---//
        $aConfig = self::getModuleConfig($this->_sHomePath . 'install/config.php');
        if(empty($aConfig) || !is_array($aConfig))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        $iCategoryId = $oLanguages->getLanguageCategory($aConfig['language_category']);

        foreach($aLanguages as $sName => $sTitle)
            $this->_updateLanguage($bInstall, $sName, $iCategoryId);

        return $oLanguages->compileLanguage(0, true) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    protected function _updateLanguage($bInstall, $sLanguage, $iCategoryId = 0)
    {
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
