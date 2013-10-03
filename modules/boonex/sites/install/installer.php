<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Sites Sites
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioInstaller');

class BxSitesInstaller extends BxDolStudioInstaller {

    function __construct($aConfig) {
        parent::__construct($aConfig);
    }

    function enable($aParams) {

        return parent::enable($aParams);
    }

    function disable($aParams) {

        return parent::disable($aParams);
    }

    function install($aParams) {

        return parent::install($aParams);
    }

    function uninstall($aParams) {

        return parent::uninstall($aParams);
    }
}

/** @} */ 
