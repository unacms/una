<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Analytics Analytics
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxAnalyticsConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array ();
        $this->_aJsClasses = array(
           'analytics' => 'BxAnalytics'
        );
        $this->_aJsObjects = array(
            'analytics' => 'oAnalytics'
        );
    }
}

/** @} */
