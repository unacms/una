<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DolphinMigration  Dolphin Migration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDolMTemplate extends BxBaseModGeneralTemplate
{
    public function __construct(&$oConfig, &$oDb)
    {
        $this -> MODULE = 'bx_dolphin_migration';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
