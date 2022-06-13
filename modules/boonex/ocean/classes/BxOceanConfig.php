<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ocean Ocean Template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModTemplateConfig');

class BxOceanConfig extends BxBaseModTemplateConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_iLogoHeight = 48;

        $this->_aPrefixes = array(
            'option' => 'bx_ocean_'
        );
    }
}

/** @} */
