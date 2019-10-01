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
 * View entry social actions menu
 */
class BxAdsMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_ads';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemInterested($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
    
    protected function _getMenuItemEditAd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteAd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
