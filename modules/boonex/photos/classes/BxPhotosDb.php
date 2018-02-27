<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxPhotosDb extends BxBaseModFilesDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
