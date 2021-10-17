<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStrmFormPoll extends BxBaseModTextFormPoll
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_stream';
        $this->_aFieldsCheckForSpam = array('answers');
        parent::__construct($aInfo, $oTemplate);
    }
}

class BxStrmFormPollCheckerHelper extends BxBaseModTextFormPollCheckerHelper {}

/** @} */
