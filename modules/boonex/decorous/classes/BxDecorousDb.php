<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Decorous Decorous template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModGeneralDb');

/*
 * Module database queries
 */
class BxDecorousDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
