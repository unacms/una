<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxClssMenuAttachments extends BxBaseModTextMenuAttachments
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_classes';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
