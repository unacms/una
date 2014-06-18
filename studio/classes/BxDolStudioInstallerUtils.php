<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolInstallerUtils');

class BxDolStudioInstallerUtils extends BxDolInstallerUtils implements iBxDolSingleton {
    public function __construct() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
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
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioInstallerUtils();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function perform($sDirectory, $sOperation, $aParams = array()) {
        $sConfigFile = 'install/config.php';
        $sInstallerFile = 'install/installer.php';
        $sInstallerClass = $sOperation == 'update' ? 'Updater' : 'Installer';

        $sPathConfig = BX_DIRECTORY_PATH_MODULES . $sDirectory . $sConfigFile;
        $sPathInstaller = BX_DIRECTORY_PATH_MODULES . $sDirectory . $sInstallerFile;
        if(file_exists($sPathConfig) && file_exists($sPathInstaller)) {
            include($sPathConfig);
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

	public function loadModules() {
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

	public function loadUpdates() {
    	$aUpdates = array();
    	$oTemplate = BxDolStudioTemplate::getInstance();

		$aInstalledPathes = $aInstalledInfo = array();
        $this->getInstalledInfo($aInstalledPathes, $aInstalledInfo);

        $sPath = BX_DIRECTORY_PATH_MODULES;
        if($rHandleVendor = opendir($sPath)) {
            while(($sVendor = readdir($rHandleVendor)) !== false) {
                if(substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor))
                    continue;

                if($rHandleModule = opendir($sPath . $sVendor . '/')) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

                        if($rHandleUpdate = @opendir($sPath . $sVendor . '/' . $sModule . '/updates/')) {
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

	protected function getConfigModule($sConfigPath, $aInstalledPathes = array(), $aInstalledInfo = array()) {
		if(!file_exists($sConfigPath)) 
			return array();

		include($sConfigPath);
        $sModulePath = $aConfig['home_dir'];
        $sTitle = bx_process_output($aConfig['title']);

        $bInstalled = !empty($aInstalledPathes) && in_array($sModulePath, $aInstalledPathes);
        $bEnabled = $bInstalled && !empty($aInstalledInfo) && (int)$aInstalledInfo[$sModulePath]['enabled'] == 1;

        $sLinkMarket = '';
        if(isset($aConfig['product_url'])) {
        	$aTmplVars = array(
            	'vendor' => $aConfig['vendor'],
				'version' => $aConfig['version'],
                'uri' => $aConfig['home_uri'],
                'title' => $aConfig['title']
			);

            $sLinkMarket = BxDolStudioTemplate::getInstance()->parseHtmlByContent(bx_html_attribute($aConfig['product_url']), $aTmplVars, array('{', '}'));
		}

		return array(
        	'name' => isset($aConfig['name']) ? $aConfig['name'] : $aConfig['home_uri'],
            'title' => $sTitle,
            'vendor' => $aConfig['vendor'],
            'version' => $aConfig['version'],
            'uri' => $aConfig['home_uri'],
            'dir' => $aConfig['home_dir'],
            'note' => isset($aConfig['note']) ? bx_process_output($aConfig['note']) : '',
            'link_market' => $sLinkMarket,
            'installed' => $bInstalled,
            'enabled' => $bInstalled && $bEnabled
		);
	}

	protected function getConfigUpdate($sConfigPathModule, $sConfigPathUpdate, $aInstalledPathes = array(), $aInstalledInfo = array()) {
		$aModule = $this->getConfigModule($sConfigPathModule, $aInstalledPathes, $aInstalledInfo);
		if(empty($aModule) || !$aModule['installed'])
			return array();

		if(!file_exists($sConfigPathUpdate))
			return array();

		include($sConfigPathUpdate);
        $sTitle = bx_process_output($aConfig['title']);

		return array(
        	'title' => $sTitle,
            'vendor' => $aConfig['vendor'],
            'version_from' => $aConfig['version_from'],
            'version_to' => $aConfig['version_to'],
            'dir' => $aConfig['home_dir'],
            'module_name' => $aModule['name'],
            'module_dir' => $aModule['dir'],
            'module_link_market' => $aModule['link_market'],
		);
	}

    private function getInstalledInfo(&$aInstalledPathes, &$aInstalledInfo) {
    	bx_import('BxDolModuleDb');
        $aModules = BxDolModuleDb::getInstance()->getModules();

        $aInstalledInfo = array();
        foreach($aModules as $aModule)
            $aInstalledInfo[$aModule['path']] = $aModule;

        $aInstalledPathes = array_keys($aInstalledInfo);
    }
}

/** @} */
