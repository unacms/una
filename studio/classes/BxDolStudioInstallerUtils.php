<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolInstallerUtils');

class BxDolStudioInstallerUtils extends BxDolInstallerUtils {
    function BxDolStudioInstallerUtils() {
        if(isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::BxDolInstallerUtils();
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
    function getInstance() {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolStudioInstallerUtils();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function perform($sDirectory, $sOperation, $aParams = array()) {
        $sConfigFile = 'install/config.php';
        $sInstallerFile = 'install/installer.php';
        $sInstallerClass = 'Installer';

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
}
/** @} */