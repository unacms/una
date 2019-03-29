<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPostsFormPoll extends BxBaseModTextFormPoll
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_posts';
        $this->_aFieldsCheckForSpam = array('answers');
        parent::__construct($aInfo, $oTemplate);
    }
}

class BxPostsFormPollCheckerHelper extends BxBaseModTextFormPollCheckerHelper {}

/** @} */
