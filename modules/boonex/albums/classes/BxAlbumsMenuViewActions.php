<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry social actions menu
 */
class BxAlbumsMenuViewActions extends BxBaseModTextMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_albums';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemAddImagesToAlbum($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditAlbum($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemDeleteAlbum($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
