<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxPhotosFormEntry extends BxBaseModFilesFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_photos';
        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
