<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

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
            'check_module_matches' => array(
                'title' => _t('_adm_txt_modules_check_module_matches'),
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
            'restore_languages' => array(
                'title' => _t('_adm_txt_modules_restore_languages'),
            ),
        ));
    }

    public function update($aParams)
    {
    	$bHtmlResponce = isset($aParams['html_response']) && (bool)$aParams['html_response'];

        $aResult = array(
            'operation_title' => _t('_adm_txt_modules_operation_update', $this->_aConfig['title'], $this->_aConfig['version_from'], $this->_aConfig['version_to'])
        );

        //--- Check for module to update ---//
        $aModuleInfo = $this->oDb->getModulesBy(array('type' => 'path_and_uri', 'path' => $this->_aConfig['module_dir'], 'uri' => $this->_aConfig['module_uri']));
        if(!$aModuleInfo)
            return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_exists', false, '_adm_err_modules_module_not_found', $bHtmlResponce),
                'result' => false
            ));

		if(isset($aParams['module_name']) && strcmp($aParams['module_name'], $aModuleInfo['name']) != 0)
			return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_matches', false, '_adm_err_modules_module_not_match', $bHtmlResponce),
                'result' => false
            ));

		//--- Check version compatibility ---//
        if(!$this->_isCompatibleWith())
            return array(
                'message' => $this->_displayResult('check_script_version', false, _t('_adm_err_modules_wrong_version_script_update', $aModuleInfo['title']), $bHtmlResponce),
                'result' => false
            );

        //--- Check version ---//
        if(version_compare($aModuleInfo['version'], $this->_aConfig['version_from']) != 0)
            return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_version', false, '_adm_err_modules_wrong_version', $bHtmlResponce),
                'result' => false
            ));

        //--- Check hash ---//
        $aFiles = array();
        $this->hashFiles(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['module_dir'], $aFiles);

        list($aFilesChanged, $fChangedPercent) = $this->hashCheck($aFiles, $aModuleInfo['id']);
        $bAutoupdateForceModifiedFiles = getParam('sys_autoupdate_force_modified_files') == 'on';

    	if(!empty($aFilesChanged) && !$bAutoupdateForceModifiedFiles) 
    		return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_hash', false, '_adm_err_modules_module_was_modified', $bHtmlResponce),
                'result' => false
            ));
		else if($fChangedPercent > BX_FORCE_AUTOUPDATE_MAX_CHANGED_FILES_PERCENT && $bAutoupdateForceModifiedFiles) 
			return array_merge($aResult, array(
                'message' => $this->_displayResult('check_module_hash', false, _t('_sys_upgrade_files_checksum_failed_too_many', round($fChangedPercent * 100)), $bHtmlResponce),
                'result' => false
            ));

        //--- Perform action and check results ---//
        $aResult = array_merge($aResult, $this->_perform('install', $aParams));
        if($aResult['result']) {
            $this->oDb->updateModule(array('version' => $this->_aConfig['version_to']), array('id' => $aModuleInfo['id']));

            $this->oDb->deleteModuleTrackFiles($aModuleInfo['id']);

            $aFiles = array();
            $this->hashFiles(BX_DIRECTORY_PATH_ROOT . 'modules/' . $this->_aConfig['module_dir'], $aFiles);
            foreach($aFiles as $aFile)
                $this->oDb->insertModuleTrack($aModuleInfo['id'], $aFile);

            //--- Remove update pckage ---//
            $this->delete($aParams);
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

		if(!$oFile->copy($sPath . '*', 'modules/' . $this->_aConfig['module_dir']))
			return BX_DOL_STUDIO_INSTALLER_FAILED;

		if(!empty($this->_aConfig['delete_files']) && is_array($this->_aConfig['delete_files']))
			foreach($this->_aConfig['delete_files'] as $sFile)
				if(!$oFile->delete('modules/' . $this->_aConfig['module_dir'] . $sFile))
					return BX_DOL_STUDIO_INSTALLER_FAILED;

        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }

    public function actionExecuteSql($sOperation)
    {
    	$aModule = $this->oDb->getModuleByUri($this->_aConfig['module_uri']);
    	if(empty($aModule))
    		return BX_DOL_STUDIO_INSTALLER_FAILED;

    	$sPathInstall = $this->_sHomePath . 'install/sql/install.sql';
    	$sPathEnable = $this->_sHomePath . 'install/sql/enable.sql';

    	$mixedResult = true;
    	if(file_exists($sPathInstall))
        	$mixedResult = $this->oDb->executeSQL($sPathInstall, $this->getMarkersForDb());

		if($mixedResult === true && (int)$aModule['enabled'] == 1 && file_exists($sPathEnable))
			$mixedResult = $this->oDb->executeSQL($sPathEnable, $this->getMarkersForDb());

        return $mixedResult === true ? BX_DOL_STUDIO_INSTALLER_SUCCESS : array('code' => BX_DOL_STUDIO_INSTALLER_FAILED, 'content' => $mixedResult);
    }

    public function actionUpdateLanguages($bInstall = true)
    {
        $aConfig = self::getModuleConfig($this->_sHomePath . 'install/config.php');
        if(empty($aConfig) || !is_array($aConfig))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        $oLanguages = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguages->getLanguages();

        $iCategoryId = !empty($aConfig['language_category']) ? $oLanguages->getLanguageCategory($aConfig['language_category']) : 0;

        foreach($aLanguages as $sName => $sTitle)
            $this->_updateLanguage($bInstall, $sName, $iCategoryId);

        return $oLanguages->compileLanguage(0, true) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    /*
     * Restore module's language files.
     * 
     * Note. Mainly the action is needed for Updates in 'language' type modules. 
     * It should be used after 'update_files' action if some changes were done in module's language files. 
     */
    public function actionRestoreLanguages($bInstall = true)
    {
    	$aConfig = self::getModuleConfig($this->_sHomePath . 'install/config.php');
        if(empty($aConfig) || !is_array($aConfig) || empty($aConfig['module_uri']))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        $oLanguages = BxDolStudioLanguagesUtils::getInstance();
        $aLanguages = $oLanguages->getLanguages();

        $bResult = true;
        foreach($aLanguages as $sName => $sTitle)
        	$bResult &= $oLanguages->restoreLanguage($sName, $aConfig['module_uri']);

        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    protected function _updateLanguage($bInstall, $sLanguage, $iCategory = 0)
    {
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $sPath = $this->_sHomePath . 'install/langs/' . $sLanguage . '.xml';
        $aLanguageInfo = $oLanguages->readLanguage($sPath, 'update');
        if(empty($aLanguageInfo))
            return false;

        $iLanguage = $oLanguages->getLangId($sLanguage);

        if(!empty($aLanguageInfo['category']))
        	$iCategory = $oLanguages->getLanguageCategory($aLanguageInfo['category']);

        //--- Process delete. Note. Deletion is performed for all languages. ---//
        if(isset($aLanguageInfo['strings_del']))
        	foreach($aLanguageInfo['strings_del'] as $sKey => $sValue)
        		$oLanguages->deleteLanguageString($sKey, 0, false);

        //--- Process add. Note. Key's category will be updated if it doesn't match. ---//
        if(isset($aLanguageInfo['strings_add']))
        	foreach($aLanguageInfo['strings_add'] as $sKey => $sValue)
        		$oLanguages->addLanguageString($sKey, $sValue, $iLanguage, $iCategory, false);

        //--- Process update. Note. Key's category will be updated if it doesn't match. ---//
        if(isset($aLanguageInfo['strings_upd']))
        	foreach($aLanguageInfo['strings_upd'] as $sKey => $sValue)
        		$oLanguages->updateLanguageString($sKey, $sValue, $iLanguage, $iCategory, false);

        return true;
    }
}

/** @} */
