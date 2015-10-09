<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Air Air
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import ('BxBaseModGeneralModule');

/**
 * Air module
 */
class BxAirModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceGetSplash()
    {
    	return $this->_oTemplate->getSplash();
    }
}

/** @} */
