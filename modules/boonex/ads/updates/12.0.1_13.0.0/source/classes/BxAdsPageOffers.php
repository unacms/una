<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAdsPageOffers extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
