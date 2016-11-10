<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolPaginate
 */
class BxBasePaginate extends BxDolPaginate
{
    function __construct($aParams, $oTemplate)
    {
        parent::__construct($aParams, $oTemplate);
    }
}

/** @} */
