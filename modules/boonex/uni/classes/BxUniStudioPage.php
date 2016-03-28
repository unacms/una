<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Uni Uni
 * @ingroup     TridentModules
 *
 * @{
 */

class BxUniStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_uni';
        parent::__construct($sModule, $sPage);
    }
}

/** @} */
