<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

require_once('BxDevFunctions.php');

define('BX_DEV_TOOLS_SETTINGS', 'settings');
define('BX_DEV_TOOLS_FORMS', 'forms');
define('BX_DEV_TOOLS_PAGES', 'pages');
define('BX_DEV_TOOLS_NAVIGATION', 'navigation');
define('BX_DEV_TOOLS_POLYGLOT', 'polyglot');
define('BX_DEV_TOOLS_PERMISSIONS', 'permissions');

class BxDevModule extends BxDolModule
{
    public $aTools;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->aTools = array(
            array('name' => BX_DEV_TOOLS_SETTINGS, 'title' => '', 'icon' => 'cogs'),
            array('name' => BX_DEV_TOOLS_FORMS, 'title' => '', 'icon' => 'bx-dev-mi-forms.png'),
            array('name' => BX_DEV_TOOLS_NAVIGATION, 'title' => '', 'icon' => 'bx-dev-mi-navigation.png'),
            array('name' => BX_DEV_TOOLS_PAGES, 'title' => '', 'icon' => 'bx-dev-mi-pages.png'),
            array('name' => BX_DEV_TOOLS_POLYGLOT, 'title' => '', 'icon' => 'bx-dev-mi-polyglot.png'),
            //array('name' => BX_DEV_TOOLS_PERMISSIONS, 'title' => '', 'icon' => 'bx-dev-mi-permissions.png'),
        );
    }

    function getToolsList()
    {
        return $this->aTools;
    }

    public function actionResetHash($sType) {
    	$oDb = bx_instance('BxDolStudioInstallerQuery');

    	$sResult = '';
    	switch ($sType) {
    		case 'system':
    			$oHasher = bx_instance('BxDolInstallerHasher');

    			$oDb->deleteModuleTrackFiles(BX_SYSTEM_MODULE_ID);

    			$sResult = _t('_bx_dev_hash_' . ($oHasher->hashSystemFiles() ? 'msg' : 'err') . '_reset_hash_system');    			
    			break;

    		case 'modules':
				$aModules = BxDolModuleQuery::getInstance()->getModules();

				$aTmplVarsModules = array();
				foreach($aModules as $aModule) {
					if($aModule['name'] == 'system')
						continue;

					$aConfig = BxDolInstallerUtils::getModuleConfig($aModule);
					$sPathInstaller = BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'install/installer.php';
					if(empty($aConfig) || !file_exists($sPathInstaller))
						continue;

					require_once($sPathInstaller);

					$sClassName = $aConfig['class_prefix'] . 'Installer';
					$oInstaller = new $sClassName($aConfig);

					$oDb->deleteModuleTrackFiles($aModule['id']);

					$aFiles = array();
				    $oInstaller->hashFiles(BX_DIRECTORY_PATH_ROOT . 'modules/' . $aModule['path'], $aFiles);
					foreach($aFiles as $aFile)
				    	$oDb->insertModuleTrack($aModule['id'], $aFile);

					$aTmplVarsModules[] = array(
						'module' => $aModule['title'],
						'files' => count($aFiles)
					);
				}

				$sResult = $this->_oTemplate->parseHtmlByName('hash_modules.html', array(
					'bx_repeat:modules' => $aTmplVarsModules
				));
				break;
    	}

    	echo $sResult;
    	exit;
    }
}
/** @} */
