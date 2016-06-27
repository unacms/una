<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Protean Protean template
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralTemplate');

class BxProteanTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_protean';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
