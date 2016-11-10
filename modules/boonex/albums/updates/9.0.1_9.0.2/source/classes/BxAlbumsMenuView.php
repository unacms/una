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
 * View entry menu
 */
class BxAlbumsMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aObject, $oTemplate);

        $sURI = bx_process_input(bx_get('i'));
        if ($sURI == 'view-album-media') {
            $aMediaInfo = $this->_oModule->_oDb->getMediaInfoById((int)bx_get('id'));
            $this->_aContentInfo = $aMediaInfo ? $this->_oModule->_oDb->getContentInfoById($aMediaInfo['content_id']) : false;
            if ($this->_aContentInfo)
                $this->addMarkers(array('content_id' => (int)$aMediaInfo['content_id']));
        }
    }
}

/** @} */
