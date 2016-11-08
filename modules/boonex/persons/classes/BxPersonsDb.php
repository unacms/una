<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Persons module database queries
 */
class BxPersonsDb extends BxBaseModProfileDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
