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

class BxPostsVotePollAnswers extends BxBaseModTextVotePollAnswers
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_posts';

        parent::__construct($sSystem, $iId, $iInit);
    }
}

/** @} */
