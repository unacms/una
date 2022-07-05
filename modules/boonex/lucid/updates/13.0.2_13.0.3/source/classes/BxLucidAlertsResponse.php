<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Lucid Lucid template
 * @ingroup     UnaModules
 *
 * @{
 */

class BxLucidAlertsResponse extends BxBaseModTemplateAlertsResponse
{
    function __construct()
    {
        $this->_sModule = 'bx_lucid';

        parent::__construct();
    }
}

/** @} */
