<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Lagoon Lagoon
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralTemplate');

class BxLagoonTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_lagoon';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
