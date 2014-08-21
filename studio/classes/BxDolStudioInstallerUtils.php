<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolInstallerUtils');

define('BX_DOL_UNITY_URL_ROOT', 'http://www.boonex.com/');
define('BX_DOL_UNITY_URL_MARKET', BX_DOL_UNITY_URL_ROOT . 'market/');

class BxDolStudioInstallerUtils extends BxDolInstallerUtils implements iBxDolSingleton
{
    public function __construct()
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
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioInstallerUtils();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function perform($sDirectory, $sOperation, $aParams = array())
    {
        $sConfigFile = 'install/config.php';
        $sInstallerFile = 'install/installer.php';
        $sInstallerClass = $sOperation == 'update' ? 'Updater' : 'Installer';

        $aConfig = self::getModuleConfig(BX_DIRECTORY_PATH_MODULES . $sDirectory . $sConfigFile);

        $sPathInstaller = BX_DIRECTORY_PATH_MODULES . $sDirectory . $sInstallerFile;
        if(!empty($aConfig) && file_exists($sPathInstaller)) {
            require_once($sPathInstaller);

            $sClassName = $aConfig['class_prefix'] . $sInstallerClass;
            $oInstaller = new $sClassName($aConfig);
            $aResult = $oInstaller->$sOperation($aParams);

            $aResult = array(
                'code' => $aResult['result'] ? 0 : 1,
                'message' => $aResult['message'],
            );
        } 
        else
            $aResult = array(
                'code' => 2,
                'message' => _t('_adm_mod_err_process_operation_failed', $sOperation, $sDirectory),
            );

        return $aResult;
    }

    public function getModules()
    {
        $aModules = array();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aInstalledPathes = $aInstalledInfo = array();
        $this->getInstalledInfo($aInstalledPathes, $aInstalledInfo);

        $sPath = BX_DIRECTORY_PATH_MODULES;
        if(($rHandleVendor = opendir($sPath)) !== false) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor))
                    continue;

                if(($rHandleModule = opendir($sPath . $sVendor)) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

                        $sConfigPath = $sPath . $sVendor . '/' . $sModule . '/install/config.php';
                        $aModule = $this->getConfigModule($sConfigPath, $aInstalledPathes, $aInstalledInfo);
                        if(empty($aModule))
                            continue;

                        $aModules[$aModule['title']] = $aModule;
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

        $aInstalledPathes = $aInstalledInfo = array();
        $this->getInstalledInfo($aInstalledPathes, $aInstalledInfo);

        $sPath = BX_DIRECTORY_PATH_MODULES;
        if(($rHandleVendor = opendir($sPath)) !== false) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor))
                    continue;

                if(($rHandleModule = opendir($sPath . $sVendor . '/')) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

                        if(($rHandleUpdate = @opendir($sPath . $sVendor . '/' . $sModule . '/updates/')) !== false) {
                            while(($sUpdate = readdir($rHandleUpdate)) !== false) {
                                if(!is_dir($sPath . $sVendor . '/' . $sModule . '/updates/' . $sUpdate) || substr($sUpdate, 0, 1) == '.')
                                    continue;

                                $sConfigPathModule = $sPath . $sVendor . '/' . $sModule . '/install/config.php';
                                $sConfigPathUpdate = $sPath . $sVendor . '/' . $sModule . '/updates/' . $sUpdate . '/install/config.php';
                                $aUpdate = $this->getConfigUpdate($sConfigPathModule, $sConfigPathUpdate, $aInstalledPathes, $aInstalledInfo);
                                if(empty($aUpdate))
                                    continue;

                                $aUpdates[$aUpdate['title']] = $aUpdate;
                            }
                            closedir($rHandleUpdate);
                        }
                    }
                    closedir($rHandleModule);
                }
            }
            closedir($rHandleVendor);
        }

        ksort($aUpdates);
        return $aUpdates;
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

        if($bAuthorizedAccess) {
	        bx_import('BxDolStudioOAuth');
	        return BxDolStudioOAuth::getInstance()->loadItems(array('dol_type' => 'available_updates', 'dol_products' => $sProducts));
        }

		bx_import('BxDolStudioJson');
		return BxDolStudioJson::getInstance()->load(BX_DOL_UNITY_URL_MARKET . 'json_browse_updates', array(
			'products' => $sProducts,
			'domain' => BX_DOL_URL_ROOT,
			'user' => (int)getParam('sys_oauth_user') 
		));
    }

    public function downloadFileAuthorized($iFileId)
    {
    	bx_import('BxDolStudioOAuth');
		$aItem = BxDolStudioOAuth::getInstance()->loadItems(array('dol_type' => 'product_file', 'dol_file_id' => $iFileId));

		return $this->downloadFile($aItem);
    }

    public function downloadUpdatePublic($sModuleName)
    {
    	bx_import('BxDolModuleQuery');
		$aModule = BxDolModuleQuery::getInstance()->getModuleByName($sModuleName);

		bx_import('BxDolStudioJson');
		$aItem = BxDolStudioJson::getInstance()->load(BX_DOL_UNITY_URL_MARKET . 'json_download_update', array(
			'product' => base64_encode(serialize(array(
				'name' => $aModule['name'],
	            'version' => $aModule['version'],
	            'hash' => $aModule['hash'],
			))),
			'domain' => BX_DOL_URL_ROOT,
			'user' => (int)getParam('sys_oauth_user') 
		));

		return $this->downloadFile($aItem);
    }

    protected function downloadFile($aItem, $bUseFtp = BX_FORCE_USE_FTP_FILE_TRANSFER)
    {
        if(empty($aItem) || !is_array($aItem))
            return $aItem;

        //--- write ZIP archive.
        $sFilePath = BX_DIRECTORY_PATH_TMP . $aItem['name'];
        if (!$rHandler = fopen($sFilePath, 'w'))
            return _t('_adm_str_err_cannot_write');

        if (!fwrite($rHandler, urldecode($aItem['content'])))
            return _t('_adm_str_err_cannot_write');

        fclose($rHandler);

        //--- Unarchive package.
        if(!class_exists('ZipArchive'))
            return _t('_adm_str_err_zip_not_available');

        $oZip = new ZipArchive();
        if($oZip->open($sFilePath) !== true)
            return _t('_adm_str_err_cannot_unzip_package');

        $sPackageRootFolder = $oZip->numFiles > 0 ? $oZip->getNameIndex(0) : false;
        if($sPackageRootFolder && file_exists(BX_DIRECTORY_PATH_TMP . $sPackageRootFolder)) // remove existing tmp folder with the same name
            bx_rrmdir(BX_DIRECTORY_PATH_TMP . $sPackageRootFolder);

        if($sPackageRootFolder && !$oZip->extractTo(BX_DIRECTORY_PATH_TMP))
            return _t('_adm_str_err_cannot_unzip_package');

        $oZip->close();

        //--- Move unarchived package.
        if($bUseFtp) {
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

        $aConfig = self::getModuleConfig(BX_DIRECTORY_PATH_TMP . $sPackageRootFolder . 'install/config.php');
        if(empty($aConfig) || !is_array($aConfig) || empty($aConfig['home_dir']))
            return _t('_adm_str_err_wrong_package_format');

        if(!$oFile->copy(BX_DIRECTORY_PATH_TMP . $sPackageRootFolder, 'modules/' . $aConfig['home_dir']))
            return _t('_adm_str_err_files_copy_failed');

        return true;
    }

    protected function getConfigModule($sConfigPath, $aInstalledPathes = array(), $aInstalledInfo = array())
    {
    	$aConfig = self::getModuleConfig($sConfigPath);
        if(empty($aConfig) || !is_array($aConfig))
            return array();

        $sModulePath = $aConfig['home_dir'];

        $bInstalled = !empty($aInstalledPathes) && in_array($sModulePath, $aInstalledPathes);
        $bEnabled = $bInstalled && !empty($aInstalledInfo) && (int)$aInstalledInfo[$sModulePath]['enabled'] == 1;

        return array(
        	'type' => $aConfig['type'],
            'name' => isset($aConfig['name']) ? $aConfig['name'] : $aConfig['home_uri'],
            'title' => bx_process_output($aConfig['title']),
            'vendor' => $aConfig['vendor'],
            'version' => $aConfig['version'],
            'uri' => $aConfig['home_uri'],
            'dir' => $aConfig['home_dir'],
            'note' => isset($aConfig['note']) ? bx_process_output($aConfig['note']) : '',
            'installed' => $bInstalled,
            'enabled' => $bInstalled && $bEnabled
        );
    }

    protected function getConfigUpdate($sConfigPathModule, $sConfigPathUpdate, $aInstalledPathes = array(), $aInstalledInfo = array())
    {
        $aModule = $this->getConfigModule($sConfigPathModule, $aInstalledPathes, $aInstalledInfo);
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
            'module_dir' => $aModule['dir']
        );
    }

    private function getInstalledInfo(&$aInstalledPathes, &$aInstalledInfo)
    {
        bx_import('BxDolModuleDb');
        $aModules = BxDolModuleDb::getInstance()->getModules();

        $aInstalledInfo = array();
        foreach($aModules as $aModule)
            $aInstalledInfo[$aModule['path']] = $aModule;

        $aInstalledPathes = array_keys($aInstalledInfo);
    }
}

/** @} */
