<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxForumFormPoll extends BxBaseModTextFormPoll
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_forum';
        $this->_aFieldsCheckForSpam = array('answers');

        parent::__construct($aInfo, $oTemplate);
    }
}

class BxForumFormPollCheckerHelper extends BxBaseModTextFormPollCheckerHelper {}

/** @} */
