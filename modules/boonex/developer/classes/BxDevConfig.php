<? defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxDevConfig extends BxDolModuleConfig {
    function BxDevConfig($aModule) {
        parent::BxDolModuleConfig($aModule);
    }
}

/** @} */