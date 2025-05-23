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

class BxTimelineFormPoll extends BxBaseModGeneralFormPoll
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';

        parent::__construct($aInfo, $oTemplate);
    }
}

class BxTimelineFormPollCheckerHelper extends BxBaseModGeneralFormPollCheckerHelper {}

/** @} */
