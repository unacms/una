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

/**
 * 'Stream manage tools' menu.
 */
class BxStrmMenuManageTools extends BxBaseModTextMenuManageTools
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_stream';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
