<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Directory Directory
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDirVotePollAnswers extends BxBaseModTextVotePollAnswers
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_directory';

        parent::__construct($sSystem, $iId, $iInit);
    }
}

/** @} */
