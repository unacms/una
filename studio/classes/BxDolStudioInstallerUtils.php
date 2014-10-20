<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolLanguages');
bx_import('BxDolInstallerUtils');

define('BX_DOL_UNITY_URL_ROOT', 'http://www.boonex.com/');
define('BX_DOL_UNITY_URL_MARKET', BX_DOL_UNITY_URL_ROOT . 'market/');

define('BX_DOL_STUDIO_IU_RC_SUCCESS', 0);
define('BX_DOL_STUDIO_IU_RC_FAILED', 1);
define('BX_DOL_STUDIO_IU_RC_SCHEDULED', 2);

class BxDolStudioInstallerUtils extends BxDolInstallerUtils implements iBxDolSingleton
{
	protected $bUseFtp;
	protected $sAuthorizedAccessClass;

    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->bUseFtp = BX_FORCE_USE_FTP_FILE_TRANSFER;
        $this->sAuthorizedAccessClass = 'BxDolStudioOAuthPlugin';
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
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioInstallerUtils();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public static function getNamePerformAction($sParam)
    {
    	return 'sys_perform_action_' . md5($sParam);
    }

    public static function getNameDownloadFile($sParam)
    {
    	return 'sys_download_file_complete_' . $sParam;
    }

    /*
     * Is used to complete Downloading inside Transient Cron Job.
     */
    public function serviceDownloadFileComplete($sFilePath, $aParams = array())
    {
    	return $this->downloadFileComplete($sFilePath, array_merge($aParams, array('transient' => true)));
    }

    /*
     * Is used to perform action inside Transient Cron Job.
     */
	public function servicePerformAction($sDirectory, $sOperation, $aParams)
    {
    	return $this->perform($sDirectory, $sOperation, array_merge($aParams, array('transient' => true)));
    }

    public function getAccessObject($bAuthorizedAccess)
    {
    	$sClass = $bAuthorizedAccess ? $this->sAuthorizedAccessClass : 'BxDolStudioJson';

		bx_import($sClass);
		return $sClass::getInstance();
    }

    public function getModules($bTitleAsKey = true)
    {
        $aModules = array();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aInstalledInfo = $this->getInstalledInfo();

        $sPath = BX_DIRECTORY_PATH_MODULES;
        if(($rHandleVendor = opendir($sPath)) !== false) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor))
                    continue;

                if(($rHandleModule = opendir($sPath . $sVendor)) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

						$sModulePath = $sVendor . '/' . $sModule . '/';
                        $aModule = $this->getConfigModule($sPath . $sModulePath . 'install/config.php', !empty($aInstalledInfo[$sModulePath]) ? $aInstalledInfo[$sModulePath] : array());
                        if(empty($aModule))
                            continue;

                        $aModules[$bTitleAsKey ? $aModule['title'] : $aModule['name']] = $aModule;
                    }
                    closedir($rHandleModule);
                }
            }
            closedir($rHandleVendor);
        }

        ksort($aModules);
        return $aModules;
    }

    public function getUpdates()
    {
        $aUpdates = array();

        bx_import('BxDolStudioTemplate');
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aInstalledInfo = $this->getInstalledInfo();

        $sPath = BX_DIRECTORY_PATH_MODULES;
        if(($rHandleVendor = opendir($sPath)) !== false) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor))
                    continue;

                if(($rHandleModule = opendir($sPath . $sVendor . '/')) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

						$sModulePath = $sVendor . '/' . $sModule . '/';
						if(empty($aInstalledInfo[$sModulePath]))
							continue;

						$aUpdate = $this->getUpdate($aInstalledInfo[$sModulePath]);
						if(!empty($aUpdate))
							$aUpdates[$aUpdate['title']] = $aUpdate;
                    }
                    closedir($rHandleModule);
                }
            }
            closedir($rHandleVendor);
        }

        ksort($aUpdates);
        return $aUpdates;
    }

    public function getUpdate($mixedModule)
    {
    	$aResult = array();

    	if(!is_array($mixedModule)) {
    		bx_import('BxDolModuleQuery');
			$mixedModule = BxDolModuleQuery::getInstance()->{is_numeric($mixedModule) ? 'getModuleById' : 'getModuleByName'}($mixedModule); 
    	}

    	if(empty($mixedModule) || !is_array($mixedModule))
    		return $aResult;

    	$sPathModule = BX_DIRECTORY_PATH_MODULES . $mixedModule['path'];
    	$sPathUpdates = $sPathModule . 'updates/';

    	$rHandleUpdate = @opendir($sPathUpdates);
		if($rHandleUpdate === false)
			return $aResult; 

		while(($sUpdate = readdir($rHandleUpdate)) !== false) {
			$sPathUpdate = $sPathUpdates . $sUpdate . '/';
			if(substr($sUpdate, 0, 1) == '.' || !is_dir($sPathUpdate))
            	continue;

			$aUpdate = $this->getConfigUpdate($sPathModule . 'install/config.php', $sPathUpdate . 'install/config.php', $mixedModule);
			if(empty($aUpdate) || !is_array($aUpdate) || version_compare($aUpdate['module_version'], $aUpdate['version_from']) != 0)
            	continue;

			$aResult = $aUpdate;
			break;
		}

		closedir($rHandleUpdate);

        return $aResult;
    }

    public function perform($sDirectory, $sOperation, $aParams = array())
    {
	    $bTransient = false;
    	if(isset($aParams['transient']) && $aParams['transient'] === true) {
    		$bTransient = true;
    		unset($aParams['transient']);
    	}

    	if(!defined('BX_DOL_CRON_EXECUTE') && !self::isRealOwner() && !in_array($sOperation, array('install', 'uninstall', 'enable', 'disable'))) {
    		if($this->addTransientJob(self::getNamePerformAction($sDirectory), 'perform_action', array($sDirectory, $sOperation, $aParams)))
    			return array('code' => BX_DOL_STUDIO_IU_RC_SCHEDULED, 'message' => _t('_adm_mod_msg_process_operation_scheduled'));
    		else 
    			return array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_mod_err_process_operation_failed', $sOperation, $sDirectory));
    	}

    	$sConfigFile = 'install/config.php';
        $sInstallerFile = 'install/installer.php';
        $sInstallerClass = $sOperation == 'update' ? 'Updater' : 'Installer';

        $aConfig = self::getModuleConfig(BX_DIRECTORY_PATH_MODULES . $sDirectory . $sConfigFile);

        $sPathInstaller = BX_DIRECTORY_PATH_MODULES . $sDirectory . $sInstallerFile;
        if(empty($aConfig) || !file_exists($sPathInstaller)) {
        	$sMessage = _t('_adm_mod_err_process_operation_failed', $sOperation, $sDirectory);
        	if($bTransient)
        		$this->emailNotify($sMessage);

        	return array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => $sMessage);
        } 

		require_once($sPathInstaller);

		$sClassName = $aConfig['class_prefix'] . $sInstallerClass;
		$oInstaller = new $sClassName($aConfig);
		$aResult = $oInstaller->$sOperation($aParams);

		if(!$aResult['result'] && $bTransient)
			$this->emailNotify($aResult['message']);

        return array('code' => $aResult['result'] ? BX_DOL_STUDIO_IU_RC_SUCCESS : BX_DOL_STUDIO_IU_RC_FAILED, 'message' => $aResult['message']);
    }

    public function checkModules($bAuthorizedAccess = false)
    {
    	if($bAuthorizedAccess)
        	$aProducts = $this->getAccessObject(true)->loadItems(array('dol_type' => 'purchased_products', 'dol_domain' => BX_DOL_URL_ROOT));
    	else
			$aProducts = $this->getAccessObject(false)->load(BX_DOL_UNITY_URL_MARKET . 'json_browse_purchased', array('key' => getParam('sys_oauth_key')));

    	bx_import('BxDolModuleQuery');
    	$oModuleDb = BxDolModuleQuery::getInstance();

    	$oModuleDb->updateModule(array('hash' => ''));
        if(!empty($aProducts) && is_array($aProducts))
	        foreach ($aProducts as $aProduct)
	        	$oModuleDb->updateModule(array('hash' => $aProduct['hash']), array('name' => $aProduct['name']));

		return $aProducts;
    }

    public function checkUpdates($bAuthorizedAccess = false)
    {
		bx_import('BxDolModuleQuery');
		$aModules = BxDolModuleQuery::getInstance()->getModules();

        $aProducts = array();
        foreach($aModules as $aModule) {
            if(empty($aModule['name']))
                continue;

            $aProducts[] = array(
                'name' => $aModule['name'],
                'version' => $aModule['version'],
            	'hash' => $aModule['hash'],
            );
        }

        $sProducts = base64_encode(serialize($aProducts));

        if($bAuthorizedAccess)
	        return $this->getAccessObject(true)->loadItems(array('dol_type' => 'available_updates', 'dol_products' => $sProducts));

		return $this->getAccessObject(false)->load(BX_DOL_UNITY_URL_MARKET . 'json_browse_updates', array(
			'key' => getParam('sys_oauth_key'),
			'products' => $sProducts
		));
    }

    public function downloadFileAuthorized($iFileId)
    {
		$aItem = $this->getAccessObject(true)->loadItems(array('dol_type' => 'product_file', 'dol_file_id' => $iFileId));

		return $this->downloadFileInit($aItem, array('module_name' => $aItem['module_name']));
    }

    public function downloadUpdatePublic($sModuleName, $bAutoUpdate = false)
    {
    	bx_import('BxDolModuleQuery');
		$aModule = BxDolModuleQuery::getInstance()->getModuleByName($sModuleName);

		$aItem = $this->getAccessObject(false)->load(BX_DOL_UNITY_URL_MARKET . 'json_download_update', array(
			'key' => getParam('sys_oauth_key'),
			'product' => base64_encode(serialize(array(
				'name' => $aModule['name'],
	            'version' => $aModule['version'],
	            'hash' => $aModule['hash'],
			))),
		));

		return $this->downloadFileInit($aItem, array('module_name' => $sModuleName, 'auto_action' => $bAutoUpdate ? 'update' : ''));
    }

    protected function downloadFileInit($aItem, $aParams = array())
    {
    	if(empty($aItem) || !is_array($aItem))
			return $aItem;

    	//--- write ZIP archive.
        $sFilePath = '';
        $mixedResult = $this->performWrite($aItem, $sFilePath);
        if($mixedResult !== true)
        	return $mixedResult;

		return $this->downloadFileComplete($sFilePath, $aParams);
    }

    protected function downloadFileComplete($sFilePath, $aParams = array())
    {
		$bTransient = false;
    	if(isset($aParams['transient']) && $aParams['transient'] === true) {
    		$bTransient = true;
    		unset($aParams['transient']);
    	}

    	$sAutoAction = isset($aParams['auto_action']) ? $aParams['auto_action'] : '';
    	$bAutoAction = !empty($sAutoAction);

		if(!defined('BX_DOL_CRON_EXECUTE') && !self::isRealOwner()) {
			if(!$this->addTransientJob(self::getNameDownloadFile($aParams['module_name']), 'download_file_complete', array($sFilePath, $aParams)))
				return _t('_adm_str_err_download_failed');

			return array('code' => BX_DOL_STUDIO_IU_RC_SCHEDULED, 'message' => _t('_adm_str_msg_download' . ($bAutoAction ? '_and_install' : '') . '_scheduled'));
		}

        //--- Unarchive package.
        $sPackagePath = '';
        $mixedResult = $this->performUnarchive($sFilePath, $sPackagePath);

        @unlink($sFilePath);
        if($mixedResult !== true) {
        	if($bTransient)
        		$this->emailNotify($mixedResult);

        	return $mixedResult;
        }

        //--- Copy unarchived package.
        $sHomePath = '';
        $mixedResult = $this->performCopy($sPackagePath, $sHomePath);

        @bx_rrmdir($sPackagePath);
        if($mixedResult !== true) {
        	if($bTransient)
        		$this->emailNotify($mixedResult);

        	return $mixedResult;
        }

		if(!$bAutoAction)
			 return true;

		//--- Autoinstall the downloaded package if it's needed.
		$aResult = $this->perform($sHomePath, $sAutoAction, $aParams);
		if((int)$aResult['code'] != 0) {
			if($bTransient)
        		$this->emailNotify($aResult['message']);

			return $aResult['message'];
		}

        return true;
    }

    protected function performWrite($aItem, &$sFilePath)
    {
    	$iUmaskSave = umask(0);

    	$sFilePath = BX_DIRECTORY_PATH_TMP . $aItem['name'];
    	if(file_exists($sFilePath))
        	@unlink($sFilePath);

        if(!$rHandler = fopen($sFilePath, 'w'))
            return _t('_adm_str_err_cannot_write');

        $sContent = urldecode($aItem['content']);
        if(!fwrite($rHandler, $sContent, strlen($sContent)))
            return _t('_adm_str_err_cannot_write');

        fclose($rHandler);
        
        umask($iUmaskSave);
        return true;
    }

    protected function performUnarchive($sFilePath, &$sPackagePath)
    {
    	$iUmaskSave = umask(0);

    	if(!class_exists('ZipArchive'))
            return _t('_adm_str_err_zip_not_available');

        $oZip = new ZipArchive();
        if($oZip->open($sFilePath) !== true)
            return _t('_adm_str_err_cannot_unzip_package');

        $sPackageFolder = '';
        if($oZip->numFiles > 0)
        	$sPackageFolder = $oZip->getNameIndex(0);

        if(empty($sPackageFolder))
        	return _t('_adm_str_err_cannot_unzip_package');
        
		$sPackagePath = BX_DIRECTORY_PATH_TMP . $sPackageFolder;
        if(file_exists($sPackagePath)) // remove existing tmp folder with the same name
            @bx_rrmdir($sPackagePath);

        if(!$oZip->extractTo(BX_DIRECTORY_PATH_TMP))
            return _t('_adm_str_err_cannot_unzip_package');

        $oZip->close();

        umask($iUmaskSave);
        return true;
    }

    protected function performCopy($sPackagePath, &$sHomePath)
    {
    	if($this->bUseFtp) {
	        $sLogin = getParam('sys_ftp_login');
	        $sPassword = getParam('sys_ftp_password');
	        $sPath = getParam('sys_ftp_dir');
	        if(empty($sLogin) || empty($sPassword) || empty($sPath))
	            return _t('_adm_str_err_no_ftp_info');
	
	        bx_import('BxDolFtp');
	        $oFile = new BxDolFtp($_SERVER['HTTP_HOST'], $sLogin, $sPassword, $sPath);
	
	        if(!$oFile->connect())
	            return _t('_adm_str_err_cannot_connect_to_ftp');
	
	        if(!$oFile->isDolphin())
	            return _t('_adm_str_err_destination_not_valid');
        }
        else {
        	bx_import('BxDolFile');
        	$oFile = BxDolFile::getInstance();
        }

        $aConfig = self::getModuleConfig($sPackagePath . 'install/config.php');
        if(empty($aConfig) || !is_array($aConfig) || empty($aConfig['home_dir']))
            return _t('_adm_str_err_wrong_package_format');

		$sHomePath = $aConfig['home_dir'];
        if(!$oFile->copy($sPackagePath, 'modules/' . $sHomePath))
            return _t('_adm_str_err_files_copy_failed');

		return true;
    }

    protected function getConfigModule($sConfigPath, $aInstalled = array())
    {
    	$aConfig = self::getModuleConfig($sConfigPath);
        if(empty($aConfig) || !is_array($aConfig))
            return array();

        $bInstalled = !empty($aInstalled);
        $bEnabled = $bInstalled && (int)$aInstalled['enabled'] == 1;
        $sVersion = $bInstalled ? $aInstalled['version'] : $aConfig['version'];

        return array(
        	'type' => $aConfig['type'],
            'name' => isset($aConfig['name']) ? $aConfig['name'] : $aConfig['home_uri'],
            'title' => bx_process_output($aConfig['title']),
            'vendor' => $aConfig['vendor'],
            'version' => $sVersion,
            'uri' => $aConfig['home_uri'],
            'dir' => $aConfig['home_dir'],
            'note' => isset($aConfig['note']) ? bx_process_output($aConfig['note']) : '',
            'installed' => $bInstalled,
            'enabled' => $bEnabled
        );
    }

    protected function getConfigUpdate($sConfigPathModule, $sConfigPathUpdate, $aInstalledModule = array())
    {
        $aModule = $this->getConfigModule($sConfigPathModule, $aInstalledModule);
        if(empty($aModule) || !$aModule['installed'])
            return array();

		$aConfig = self::getModuleConfig($sConfigPathUpdate);
        if(empty($aConfig) || !is_array($aConfig))
            return array();

        return array(
            'title' => bx_process_output($aConfig['title']),
            'vendor' => $aConfig['vendor'],
            'version_from' => $aConfig['version_from'],
            'version_to' => $aConfig['version_to'],
            'dir' => $aConfig['home_dir'],
        	'module_type' => $aModule['type'],
            'module_name' => $aModule['name'],
            'module_dir' => $aModule['dir'],
        	'module_version' => $aModule['version']
        );
    }

    private function getInstalledInfo()
    {
        bx_import('BxDolModuleQuery');
        $aModules = BxDolModuleQuery::getInstance()->getModules();

        $aInstalledInfo = array();
        foreach($aModules as $aModule)
            $aInstalledInfo[$aModule['path']] = $aModule;

        return $aInstalledInfo;
    }

    private function addTransientJob($sName, $sAction, $aParams)
    {
		bx_import('BxDolCronQuery');
		if(BxDolCronQuery::getInstance()->addTransientJobService($sName, array('system', $sAction, $aParams, 'DolStudioInstallerUtils')))
			return true;

		return false;
    }

    private function emailNotify($sMessage)
    {
    	sendMailTemplateSystem('t_BgOperationFailed', array (
			'conclusion' => strip_tags($sMessage),
		));
    }
}

/** @} */
