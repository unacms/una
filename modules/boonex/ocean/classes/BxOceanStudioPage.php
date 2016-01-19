<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Ocean Ocean
 * @ingroup     TridentModules
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
