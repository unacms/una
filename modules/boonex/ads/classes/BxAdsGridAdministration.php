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

class BxAdsGridAdministration extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_ads';

        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
