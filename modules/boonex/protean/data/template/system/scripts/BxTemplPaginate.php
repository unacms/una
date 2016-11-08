<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolPaginate
 */
class BxTemplPaginate extends BxBasePaginate
{
    function __construct($aParams, $oTemplate = false)
    {
        parent::__construct($aParams, $oTemplate);
    }
}

/** @} */
