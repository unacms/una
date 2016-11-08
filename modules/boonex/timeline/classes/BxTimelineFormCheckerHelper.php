<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolStudioForm');

class BxTimelineFormCheckerHelper extends BxDolStudioFormCheckerHelper
{
    function checkGreaterThan($sVal, $iLimit)
    {
        return (int)$sVal > (int)$iLimit;
    }
}

/** @} */
