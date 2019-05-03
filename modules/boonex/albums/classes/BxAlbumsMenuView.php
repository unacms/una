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

        $this->addMarkers(array('js_object' => $this->_oModule->_oConfig->getJsObject('main')));

        $sURI = bx_process_input(bx_get('i'));
        if ($sURI == 'view-album-media') {
            $iMediaId = (int)bx_get('id');
            $aMediaInfo = $this->_oModule->_oDb->getMediaInfoById($iMediaId);
            $this->addMarkers(array('media_id' => $iMediaId));

            $this->_aContentInfo = $aMediaInfo ? $this->_oModule->_oDb->getContentInfoById($aMediaInfo['content_id']) : false;
            if ($this->_aContentInfo)
                $this->addMarkers(array('content_id' => (int)$aMediaInfo['content_id']));
        }
    }
}

/** @} */
