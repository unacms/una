<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolInstallerUtils');

define("BX_DOL_STUDIO_INSTALLER_SUCCESS", 0);
define("BX_DOL_STUDIO_INSTALLER_FAILED", 1);

/**
 * Base class for Installer classes in modules engine.
 *
 * The class contains different check functions which are used during the installation process.
 * An object of the class is created automatically with Dolphin's modules installer.
 * Installation/Uninstallation process can be controlled with config.php file located in  [module]/install/ folder.
 *
 *
 * Example of usage:
 * @see any module included in the default Dolphin's package.
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 * Alerts:
 * no alerts available
 *
 */

class BxDolStudioInstaller extends BxDolInstallerUtils
{
    protected $oDb;

    protected $_aConfig;
    protected $_sBasePath;
    protected $_sHomePath;
    protected $_sModulePath;

    protected $_bUseFtp;
    protected $_aActions;

    protected $_bShowOnSuccess = false;

    function __construct($aConfig)
    {
        parent::__construct();

        $this->oDb = bx_instance('BxDolStudioInstallerQuery');

        $this->_aConfig = $aConfig;
        $this->_sBasePath = BX_DIRECTORY_PATH_MODULES;
        $this->_sHomePath = $this->_sBasePath . $aConfig['home_dir'];
        $this->_sModulePath = $this->_sBasePath . $aConfig['home_dir'];

        $this->_bUseFtp = BX_FORCE_USE_FTP_FILE_TRANSFER;

        $this->_aActions = array(
            'perform_install' => array(
                'title' => '',
                'success' => _t('_adm_msg_modules_success_install'),
                'failed' => ''
            ),
            'perform_uninstall' => array(
                'title' => '',
                'success' => _t('_adm_msg_modules_success_uninstall'),
                'failed' => ''
            ),
            'perform_enable' => array(
                'title' => '',
                'success' => _t('_adm_msg_modules_success_enable'),
                'failed' => ''
            ),
            'perform_disable' => array(
                'title' => '',
                'success' => _t('_adm_msg_modules_success_disable'),
                'failed' => ''
            ),
            'check_script_version' => array(
                'title' => _t('_adm_txt_modules_check_script_version'),
            ),
            'check_dependencies' => array(
                'title' => _t('_adm_txt_modules_check_dependencies'),
            ),
            'show_introduction' => array(
                'title' => _t('_adm_txt_modules_show_introduction'),
            ),
            'move_sources' => array(
                'title' => _t('_adm_txt_modules_move_sources'),
            ),
            'execute_sql' => array(
                'title' => _t('_adm_txt_modules_execute_sql'),
            ),
            'install_language' => array(
                'title' => _t('_adm_txt_modules_install_language'),
            ),
            'update_languages' => array(
                'title' => _t('_adm_txt_modules_update_languages'),
            ),
            'update_relations' => array(
                'title' => _t('_adm_txt_modules_update_relations'),
            ),
            'process_deleted_profiles' => array(
                'title' => _t('_adm_txt_modules_process_deleted_profiles'),
            ),
            'recompile_global_paramaters' => array(
                'title' => _t('_adm_txt_modules_recompile_global_paramaters'),
            ),
            'recompile_menus' => array(
                'title' => _t('_adm_txt_modules_recompile_menus'),
            ),
            'recompile_site_stats' => array(
                'title' => _t('_adm_txt_modules_recompile_site_stats'),
            ),
            'recompile_comments' => array(
                'title' => _t('_adm_txt_modules_recompile_comments'),
            ),
            'recompile_member_actions' => array(
                'title' => _t('_adm_txt_modules_recompile_member_actions'),
            ),
            'recompile_votes' => array(
                'title' => _t('_adm_txt_modules_recompile_votes'),
            ),
            'recompile_search' => array(
                'title' => _t('_adm_txt_modules_recompile_search'),
            ),
            'recompile_injections' => array(
                'title' => _t('_adm_txt_modules_recompile_injections'),
            ),
            'recompile_permalinks' => array(
                'title' => _t('_adm_txt_modules_recompile_permalinks'),
            ),
            'recompile_alerts' => array(
                'title' => _t('_adm_txt_modules_recompile_alerts'),
            ),
            'clear_db_cache' => array(
                'title' => _t('_adm_txt_modules_clear_db_cache'),
            ),
            'show_conclusion' => array(
                'title' => _t('_adm_txt_modules_show_conclusion'),
            ),
        );

        $this->_aNonHashableFiles = array(
            'install',
            'updates'
        );
    }

    public function install($aParams, $bAutoEnable = false)
    {
    	$bAutoEnable = $bAutoEnable || (isset($aParams['auto_enable']) && (bool)$aParams['auto_enable']);
    	$bHtmlResponce = isset($aParams['html_response']) && (bool)$aParams['html_response'];

        //--- Check whether the module was already installed ---//
        if($this->oDb->isModule($this->_aConfig['home_uri']))
            return array(
                'message' => _t('_adm_err_modules_already_installed'),
                'result' => false
            );

        $aResult = array();
        bx_alert('system', 'before_install', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        if ($aResult && !$aResult['result'])
            return $aResult;

        //--- Check mandatory settings ---//
        if($this->oDb->isModuleParamsUsed($this->_aConfig['home_uri'], $this->_aConfig['home_dir'], $this->_aConfig['db_prefix'], $this->_aConfig['class_prefix']))
            return array(
                'message' => _t('_adm_txt_modules_params_used'),
                'result' => false
            );

        //--- Check version compatibility ---//
        if(!$this->_isCompatibleWith())
            return array(
                'message' => $this->_displayResult('check_script_version', false, '_adm_err_modules_wrong_version_script', $bHtmlResponce),
                'result' => false
            );

        //--- Check for available translations ---//
        $oFile = $this->_getFileManager();
        $sModuleUri = $this->_aConfig['home_uri'];

        $aLanguages = $this->oDb->getModulesBy(array('type' => 'languages'));
        foreach($aLanguages as $aLanguage) {
            if($aLanguage['uri'] == 'en')
                continue;

			$aLanguageConfig = self::getModuleConfig(BX_DIRECTORY_PATH_MODULES . $aLanguage['path'] . '/install/config.php');
			if(empty($aLanguageConfig))
				continue;

            if(!isset($aLanguageConfig['includes'][$sModuleUri]) || empty($aLanguageConfig['includes'][$sModuleUri]))
                continue;

            $sSrcPath = 'modules/' . $aLanguage['path'] . 'install/data/' . $aLanguageConfig['includes'][$sModuleUri];
            $sDstPath = $aLanguageConfig['includes'][$sModuleUri];
            $oFile->copy($sSrcPath, $sDstPath);
        }

        //--- Check actions ---//
        $aResult = $this->_perform('install', $aParams);
        if($aResult['result']) {
            $sDependencies = "";
            if(isset($this->_aConfig['install']['check_dependencies']) && (int)$this->_aConfig['install']['check_dependencies'] == 1 && isset($this->_aConfig['dependencies']) && is_array($this->_aConfig['dependencies']))
                $this->_aConfig['dependencies'] = array_keys($this->_aConfig['dependencies']);

            $iModuleId = $this->oDb->insertModule($this->_aConfig);

            bx_import('BxDolModule');
            $sTitleKey = BxDolModule::getTitleKey($this->_aConfig['home_uri']);

            bx_import('BxDolStudioLanguagesUtils');
            $oLanguages = BxDolStudioLanguagesUtils::getInstance();
            $oLanguages->addLanguageString($sTitleKey, $this->_aConfig['title']);
            $oLanguages->compileLanguage();

            bx_import('BxDolStudioInstallerUtils');
            BxDolStudioInstallerUtils::getInstance()->checkModules();

            $aFiles = array();
            $this->hashFiles($this->_sModulePath, $aFiles);

            foreach($aFiles as $aFile)
                $this->oDb->insertModuleTrack($iModuleId, $aFile);

            $this->oDb->cleanMemory('sys_modules_' . $this->_aConfig['home_uri']);
            $this->oDb->cleanMemory('sys_modules_' . $iModuleId);
            $this->oDb->cleanMemory('sys_modules');
        }

        bx_alert('system', 'install', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));

	    if($aResult['result'] && $bAutoEnable) {
			$aResultEnable = $this->enable($aParams);

			$aResult['result'] = $aResult['result'] & $aResultEnable['result'];
			$aResult['message'] = $aResult['message'] . $aResultEnable['message'];
		}

        return $aResult;
    }

    public function uninstall($aParams, $bAutoDisable = false)
    {
    	$bAutoDisable = $bAutoDisable || (isset($aParams['auto_disable']) && (bool)$aParams['auto_disable']);
    	$bHtmlResponce = isset($aParams['html_response']) && (bool)$aParams['html_response'];

        //--- Check whether the module was already uninstalled ---//
        if(!$this->oDb->isModule($this->_aConfig['home_uri']))
            return array(
                'message' => _t('_adm_err_modules_already_uninstalled'),
                'result' => false
            );

		if($bAutoDisable) {
            $aResultDisable = $this->disable($aParams);
            if(!$aResultDisable['result'])
            	return $aResultDisable;
        }

        $aResult = array();
        bx_alert('system', 'before_uninstall', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        if ($aResult && !$aResult['result'])
            return $aResult;

        //--- Check for dependent modules ---//
        $bDependent = false;
        $aDependents = $this->oDb->getDependent($this->_aConfig['home_uri']);
        if(is_array($aDependents) && !empty($aDependents)) {
            $bDependent = true;

            $sMessage = '<br />' . _t('_adm_err_modules_wrong_dependency_uninstall') . '<br />';
            foreach($aDependents as $aDependent)
                $sMessage .= $aDependent['title'] . '<br />';
        }

        if($bDependent)
            return array(
                'message' => $this->_displayResult('check_dependencies', false, $sMessage, $bHtmlResponce),
                'result' => false
            );

        $aResult = $this->_perform('uninstall', $aParams);
        if($aResult['result']) {
            $iModuleId = $this->oDb->deleteModule($this->_aConfig);

            bx_import('BxDolModule');
            $sTitleKey = BxDolModule::getTitleKey($this->_aConfig['home_uri']);

            bx_import('BxDolStudioLanguagesUtils');
            $oLanguages = BxDolStudioLanguagesUtils::getInstance();
            $oLanguages->deleteLanguageString($sTitleKey);
            $oLanguages->compileLanguage();

            $this->oDb->cleanMemory ('sys_modules_' . $this->_aConfig['home_uri']);
            $this->oDb->cleanMemory ('sys_modules_' . $iModuleId);
            $this->oDb->cleanMemory ('sys_modules');
        }

        if($bAutoDisable) {
	        $aResult['result'] = $aResultDisable['result'] & $aResult['result'];
			$aResult['message'] = $aResultDisable['message'] . $aResult['message'];
        }

        bx_alert('system', 'uninstall', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        return $aResult;
    }

    public function delete($aParams)
    {
    	$aError = array(
			'message' => _t('_adm_err_modules_cannot_remove_package'),
			'result' => false
		);

    	$oFile = $this->_getFileManager();
    	if(empty($oFile))
    		return $aError;

        if(!$oFile->delete('modules/' . $this->_aConfig['home_dir']))
            return $aError;

        return array(
            'message' => '', //_t('_adm_msg_modules_success_delete'),
            'result' => true
        );
    }

    public function recompile($aParams)
    {
        bx_import('BxDolStudioLanguagesUtils');
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $aResult = array('message' => '', 'result' => false);

        $aLanguages = $this->oDb->getLanguages();
        if (isAdmin() && !empty($aLanguages)) {
            $this->_updateLanguage(false, current($aLanguages));

            $bResult = false;
            foreach($aLanguages as $aLanguage) {
                $bResult = $this->_updateLanguage(true, $aLanguage) && $oLanguages->compileLanguage($aLanguage['id']);
                $aResult['message'] .= $aLanguage['title'] . ': <span style="color:' . ($bResult ? 'green' : 'red') . '">' . _t($bResult ? '_adm_txt_modules_process_action_success' : '_adm_txt_modules_process_action_failed') . '</span><br />';

                $aResult['result'] |= $bResult;
            }
        }

        return $aResult;
    }

    public function enable($aParams)
    {
        $aModule = $this->oDb->getModuleByUri($this->_aConfig['home_uri']);

        //--- Check whether the module is installed ---//
        if(empty($aModule) || !is_array($aModule))
            return array(
                'message' => _t('_adm_err_modules_module_not_installed'),
                'result' => false
            );

        //--- Check whether the module is already enabled ---//
        if((int)$aModule['enabled'] != 0)
            return array(
                'message' => _t('_adm_err_modules_already_enabled'),
                'result' => false
            );

		$aResult = array();
        bx_alert('system', 'before_enable', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        if ($aResult && !$aResult['result'])
            return $aResult;

        $aResult = $this->_perform('enable', $aParams);
        if($aResult['result']) {
            $this->oDb->enableModuleByUri($aModule['uri']);

            $this->oDb->cleanMemory('sys_modules_' . $aModule['uri']);
            $this->oDb->cleanMemory('sys_modules_' . $aModule['id']);
            $this->oDb->cleanMemory('sys_modules');
        }

        bx_alert('system', 'enable', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        return $aResult;
    }

    public function disable($aParams)
    {
    	$bHtmlResponce = isset($aParams['html_response']) && (bool)$aParams['html_response'];

        $aModule = $this->oDb->getModuleByUri($this->_aConfig['home_uri']);

        //--- Check whether the module is installed ---//
        if(empty($aModule) || !is_array($aModule))
            return array(
                'message' => _t('_adm_err_modules_module_not_installed'),
                'result' => false
            );

        //--- Check whether the module is already disabled ---//
        if((int)$aModule['enabled'] == 0)
            return array(
                'message' => _t('_adm_err_modules_already_disabled'),
                'result' => false
            );

		$aResult = array();
        bx_alert('system', 'before_disable', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        if ($aResult && !$aResult['result'])
            return $aResult;

        //--- Check for dependent modules ---//
        $bDependent = false;
        $aDependents = $this->oDb->getDependent($this->_aConfig['home_uri']);
        if(is_array($aDependents) && !empty($aDependents)) {
            $bDependent = true;

            $sMessage = '<br />' . _t('_adm_err_modules_wrong_dependency_disable') . '<br />';
            foreach($aDependents as $aDependent)
                $sMessage .= $aDependent['title'] . '<br />';
        }

        if($bDependent)
            return array(
                'message' => $this->_displayResult('check_dependencies', false, $sMessage, $bHtmlResponce),
                'result' => false
            );

        $aResult = $this->_perform('disable', $aParams);
        if($aResult['result']) {
            $this->oDb->disableModuleByUri($aModule['uri']);

            $this->oDb->cleanMemory('sys_modules_' . $aModule['uri']);
            $this->oDb->cleanMemory('sys_modules_' . $aModule['id']);
            $this->oDb->cleanMemory('sys_modules');
        }

        bx_alert('system', 'disable', 0, false, array ('config' => $this->_aConfig, 'result' => &$aResult));
        return $aResult;
    }

    protected function filePathWithoutBase($sPath)
    {
        return bx_ltrim_str($sPath, $this->_sModulePath);
    }

    function _perform($sOperationName, $aParams = array())
    {
    	$bHtmlResponce = isset($aParams['html_response']) && (bool)$aParams['html_response'];

        if(!defined('BX_SKIP_INSTALL_CHECK') && !defined('BX_DOL_CRON_EXECUTE') && !$GLOBALS['logged']['admin'])
            return array('message' => '_adm_mod_err_only_admin_can_perform_operations_with_modules', 'result' => false);

        $sMessage = '';
        foreach($this->_aConfig[$sOperationName] as $sAction => $iEnabled) {
            $sMethod = 'action' . bx_gen_method_name($sAction);
            if($iEnabled == 0 || !method_exists($this, $sMethod))
                continue;

            $mixedResult = $this->$sMethod($sOperationName);

            //--- On Success ---//
            if((is_int($mixedResult) && (int)$mixedResult == BX_DOL_STUDIO_INSTALLER_SUCCESS) || (isset($mixedResult['code']) && (int)$mixedResult['code'] == BX_DOL_STUDIO_INSTALLER_SUCCESS)) {
                $sMessage .= $this->_displayResult($sAction, true, isset($mixedResult['content']) ? $mixedResult['content'] : '', $bHtmlResponce);
                continue;
            }

            //--- On Failed ---//
            $sMethodFailed = $sMethod . 'Failed';
            return array('message' => $this->_displayResult($sAction, false, method_exists($this, $sMethodFailed) ? $this->$sMethodFailed($mixedResult) : $this->actionOperationFailed($mixedResult), $bHtmlResponce), 'result' => false);
        }

        if($this->_bShowOnSuccess)
            $sMessage = $this->_aActions['perform_' . $sOperationName]['success'] . $sMessage;

        return array('message' => $sMessage, 'result' => true);
    }

    function _displayResult($sAction, $bResult, $sResult = '', $bHtmlResponse = true)
    {
    	if($bResult && !in_array($sAction, array('show_introduction', 'show_conclusion')) && !$this->_bShowOnSuccess)
            return '';

        $sTitle = $this->_aActions[$sAction]['title'] . ' ';
        if(!empty($sResult))
            $sResult = (substr($sResult, 0, 1) == '_' ? _t($sResult) : $sResult) . '<br />';

		$sContent = !empty($sResult) ? $sResult : _t($bResult ? '_adm_txt_modules_process_action_success' : '_adm_err_modules_process_action_failed') . '<br />';
		if(!$bHtmlResponse)
			return $sTitle . $sContent;

		bx_import('BxDolStudioTemplate');
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('mod_action_result_step.html', array(
        	'color' => $bResult ? 'green' : 'red',
            'title' => $sTitle,
            'content' => $sContent
        ));
    }

    //--- Action Methods ---//
    function actionOperationFailed($mixedResult)
    {
        return _t('_adm_err_modules_process_action_failed');
    }
    function actionCheckDependencies($sOperation)
    {
        $sContent = '';

        if(in_array($sOperation, array('install', 'enable', 'update'))) {
            if(!isset($this->_aConfig['dependencies']) || !is_array($this->_aConfig['dependencies']))
                return BX_DOL_STUDIO_INSTALLER_SUCCESS;

            foreach($this->_aConfig['dependencies'] as $sModuleUri => $sModuleTitle)
                if(!$this->oDb->isModule($sModuleUri))
                    $sContent .= $sModuleTitle . '<br />';

            if(!empty($sContent))
                $sContent = '<br />' . _t('_adm_err_modules_wrong_dependency_install') . '<br />' . $sContent;
        }

        return empty($sContent) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : array('code' => BX_DOL_STUDIO_INSTALLER_FAILED, 'content' => $sContent);
    }
    function actionCheckDependenciesFailed($mixedResult)
    {
        return $mixedResult['content'];
    }
    function actionShowIntroduction($sOperation)
    {
        if(!isset($this->_aConfig[$sOperation . '_info']['introduction']))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        $sPath = $this->_sHomePath . 'install/info/' . $this->_aConfig[$sOperation . '_info']['introduction'];
        return file_exists($sPath) ? array("code" => BX_DOL_STUDIO_INSTALLER_SUCCESS, "content" => file_get_contents($sPath)) : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionShowConclusion($sOperation)
    {
        if(!isset($this->_aConfig[$sOperation . '_info']['conclusion']))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        $sPath = $this->_sHomePath . 'install/info/' . $this->_aConfig[$sOperation . '_info']['conclusion'];
        return file_exists($sPath) ? array("code" => BX_DOL_STUDIO_INSTALLER_SUCCESS, "content" => file_get_contents($sPath)) : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionMoveSources($sOperation)
    {
        $oFile = $this->_getFileManager();
        $aInstalled = array_merge(array('system'), $this->oDb->getModulesUri());

        $bResult = true;
        foreach($this->_aConfig['includes'] as $sUri => $sPath) {
            if(!in_array($sUri, $aInstalled) || empty($sPath))
                continue;

            if($sOperation == 'install') {
                $sSrcPath = 'modules/' . $this->_aConfig['home_dir'] . 'install/data/' . $sPath;
                $sDstPath = $sPath;
                $bResult &= $oFile->copy($sSrcPath, $sDstPath);
            } else if($sOperation == 'uninstall')
                $bResult &= $oFile->delete($sPath);
        }

        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionExecuteSql($sOperation)
    {
        switch($sOperation) {
            case 'install':
                $this->actionExecuteSql('disable');
                $this->actionExecuteSql('uninstall');
                break;
            case 'enable':
                $this->actionExecuteSql('disable');
                break;
        }

        bx_import('BxDolDb');
        $mixedResult = BxDolDb::getInstance()->executeSQL($this->_sHomePath . 'install/sql/' . $sOperation . '.sql');

        return $mixedResult === true ? BX_DOL_STUDIO_INSTALLER_SUCCESS : array('code' => BX_DOL_STUDIO_INSTALLER_FAILED, 'content' => $mixedResult);
    }
    function actionExecuteSqlFailed($mixedResult)
    {
        $sResult = '<br />' . _t('_adm_err_modules_wrong_mysql_query') . '<br />';
        foreach($mixedResult['content'] as $aQuery) {
            $sResult .= _t('_adm_err_modules_wrong_mysql_query_msg', $aQuery['error']) . '<br />';
            $sResult .= '<pre>' . $aQuery['query'] . '</pre>';
        }
        return $sResult;
    }
    function actionInstallLanguage($sOperation)
    {
        bx_import('BxDolStudioLanguagesUtils');
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $sLanguage = isset($this->_aConfig['home_uri']) ? $this->_aConfig['home_uri'] : '';

        $bResult = true;
        if($sOperation == 'install')
            $bResult = $oLanguages->installLanguage(array('path' => $this->_aConfig['home_dir'], 'uri' => $this->_aConfig['home_uri'], 'lang_category' => $this->_aConfig['language_category']),false);

        return $bResult && $oLanguages->compileLanguage(0, true) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionUpdateLanguages($sOperation)
    {
        if(!isset($this->_aConfig['language_category']) || empty($this->_aConfig['language_category']))
            return BX_DOL_STUDIO_INSTALLER_FAILED;

        bx_import('BxDolStudioLanguagesUtils');
        $oLanguages = BxDolStudioLanguagesUtils::getInstance();

        $bResult = true;
        $aResult = array();

        //--- Process Language Category ---//
        if($sOperation == 'install')
            $iCategoryId = $oLanguages->addLanguageCategory($this->_aConfig['language_category']);

        //--- Process languages' key=>value pears ---//
        $aModule = array(
            'path' => $this->_aConfig['home_dir'],
            'uri' => $this->_aConfig['home_uri'],
            'lang_category' => $this->_aConfig['language_category']
        );

        if($sOperation == 'install')
            $bResult = $oLanguages->restoreLanguage(0, $aModule, false);
        else if($sOperation == 'uninstall')
            $bResult = $oLanguages->removeLanguageByModule($aModule, false);

        if($sOperation == 'uninstall' && $bResult)
            $oLanguages->deleteLanguageCategory($this->_aConfig['language_category']);

        return $bResult && $oLanguages->compileLanguage(0, true) ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
	function actionUpdateRelations($sOperation)
    {
        if(!in_array($sOperation, array('install', 'uninstall', 'enable', 'disable'))) 
        	return BX_DOL_STUDIO_INSTALLER_FAILED;

		if(empty($this->_aConfig['relations']) || !is_array($this->_aConfig['relations']))
            return BX_DOL_STUDIO_INSTALLER_SUCCESS;

		foreach($this->_aConfig['relations'] as $sModule) {
			if(!$this->oDb->isModuleByName($sModule))
				continue;

			$aRelation = $this->oDb->getRelationsBy(array('type' => 'module', 'value' => $sModule));
			if(empty($aRelation) || empty($aRelation['on_' . $sOperation]) || !BxDolRequest::serviceExists($aRelation['module'], $aRelation['on_' . $sOperation]))
				continue;

			BxDolService::call($aRelation['module'], $aRelation['on_' . $sOperation], array($this->_aConfig['home_uri']));
		}

        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }
	function actionProcessDeletedProfiles($sOperation)
    {
        if(!in_array($sOperation, array('install', 'uninstall', 'enable', 'disable'))) 
        	return BX_DOL_STUDIO_INSTALLER_FAILED;

        bx_import('BxDolProfileQuery');
        $o = BxDolProfileQuery::getInstance();
        $o->processDeletedProfiles();

        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }
    function actionRecompileGlobalParamaters($sOperation)
    {
        $bResult = $this->oDb->cacheParamsClear();
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileMenus($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_objects_menu');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileSiteStats($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_stat_site');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileComments($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_objects_cmts');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileMemberActions($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_objects_actions');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileVotes($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_objects_vote');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileInjections($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_injections.inc');
        $bResult = $bResult && $this->oDb->cleanCache('sys_injections_admin.inc');

        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompilePermalinks($sOperation)
    {
        $bResult = true;

        bx_import('BxDolPermalinks');
        $bResult = $bResult && BxDolPermalinks::getInstance()->cacheInvalidate();

        $bResult = $bResult && ($this->actionRecompileMenus($sOperation) == BX_DOL_STUDIO_INSTALLER_SUCCESS);

        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionRecompileAlerts($sOperation)
    {
        $bResult = $this->oDb->cleanCache('sys_alerts');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }
    function actionClearDbCache($sOperation)
    {
        $oCache = $this->oDb->getDbCacheObject();

        $bResult = $oCache->removeAllByPrefix('db_');
        return $bResult ? BX_DOL_STUDIO_INSTALLER_SUCCESS : BX_DOL_STUDIO_INSTALLER_FAILED;
    }

    //--- Get/Set Methods ---//
    function getVendor()
    {
        return $this->_aConfig['vendor'];
    }
    function getName()
    {
        return $this->_aConfig['name'];
    }
    function getTitle()
    {
        return $this->_aConfig['title'];
    }
    function getHomeDir()
    {
        return $this->_aConfig['home_dir'];
    }

    //--- Protected methods ---//
    protected function _getFileManager()
    {
        $oFile = null;

        if($this->_bUseFtp) {
            bx_import('BxDolFtp');
            $oFile = new BxDolFtp(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost', getParam('sys_ftp_login'), getParam('sys_ftp_password'), getParam('sys_ftp_dir'));

            if(!$oFile->connect())
                return null;

            if(!$oFile->isDolphin())
                return null;
        }
        else {
            bx_import('BxDolFile');
            $oFile = BxDolFile::getInstance();
        }

        return $oFile;
    }

    protected function _isCompatibleWith()
    {
    	$sVersionCur = bx_get_ver();

    	$bCompatible = false;
        if(isset($this->_aConfig['compatible_with']) && is_array($this->_aConfig['compatible_with']))
            foreach($this->_aConfig['compatible_with'] as $iKey => $sVersionReq)
            	$bCompatible = $bCompatible || (version_compare($sVersionCur, $sVersionReq, '>=') == 1);

		return $bCompatible;
    }
}

/** @} */
