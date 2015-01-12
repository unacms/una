<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
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
