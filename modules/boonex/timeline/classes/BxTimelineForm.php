<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralFormEntry');

/**
 * Create/Edit entry form
 */
class BxTimelineForm extends BxBaseModGeneralFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_timeline';

        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
