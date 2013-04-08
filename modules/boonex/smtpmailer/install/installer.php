<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');

class BxSMTPInstaller extends BxDolStudioInstaller {

    function __construct($aConfig) {
        parent::__construct($aConfig);
    }

    function install($aParams) {
        $aResult = parent::install($aParams);

        return $aResult;
    }

    function uninstall($aParams) {

        return parent::uninstall($aParams);
    }    
}

/** @} */ 
