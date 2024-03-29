<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * List entry page
 */
class BxAdsPageListEntry extends BxBaseModTextPageListEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
