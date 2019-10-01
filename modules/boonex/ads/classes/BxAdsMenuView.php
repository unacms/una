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

/**
 * View entry menu
 */
class BxAdsMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_ads';

        parent::__construct($aObject, $oTemplate);
    }

    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        $this->addMarkers(array('js_object' => $this->_oModule->_oConfig->getJsObject('entry')));
    }
}

/** @} */
