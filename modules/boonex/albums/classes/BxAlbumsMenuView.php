<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
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
