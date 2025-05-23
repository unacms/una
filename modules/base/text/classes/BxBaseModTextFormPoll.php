<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextFormPoll extends BxBaseModGeneralFormPoll
{
    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }
}

class BxBaseModTextFormPollCheckerHelper extends BxBaseModGeneralFormPollCheckerHelper {}

/** @} */
