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


class BxOceanAlertsResponse extends BxBaseModTemplateAlertsResponse
{
    function __construct()
    {
        $this->_sModule = 'bx_ocean';

        parent::__construct();
    }

    protected function _processSystemChangeLogo($oAlert)
    {
        $sPrefix = $this->_oModule->_oConfig->getPrefix('option');

        if(!in_array($oAlert->aExtras['option'], ['sys_site_logo', $sPrefix . 'site_logo']))
            return;

        setParam($sPrefix . 'site_logo_aspect_ratio', '');
    }
}

/** @} */
