<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Charts Charts
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxChartsConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array ();
        $this->_aJsClasses = array(
           'chart' => 'BxCharts'
        );
        $this->_aJsObjects = array(
            'chart' => 'oCharts'
        );
    }
    
}

/** @} */
