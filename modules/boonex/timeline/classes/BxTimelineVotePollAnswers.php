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

class BxTimelineVotePollAnswers extends BxBaseModGeneralVotePollAnswers
{
    public function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_timeline';

        parent::__construct($sSystem, $iId, $iInit);
    }
}

/** @} */
