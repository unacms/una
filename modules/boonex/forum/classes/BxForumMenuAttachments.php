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

class BxForumMenuAttachments extends BxBaseModTextMenuAttachments
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_forum';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
