<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Social Engine Migration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxSEMigTemplate extends BxBaseModGeneralTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        $this -> MODULE = 'bx_se_migration';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
