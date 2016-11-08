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

bx_import('BxBaseModGeneralDb');

/*
 * Module database queries
 */
class BxOceanDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
