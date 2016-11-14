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

class BxOceanStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_ocean';
        parent::__construct($sModule, $sPage);
    }
}

/** @} */
