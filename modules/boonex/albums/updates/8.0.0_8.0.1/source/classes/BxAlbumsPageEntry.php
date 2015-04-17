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
 * Entry create/edit pages
 */
class BxAlbumsPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_albums';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getThumbForMetaObject ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!($aMediaList = $this->_oModule->_oDb->getMediaListByContentId($this->_aContentInfo[$CNF['FIELD_ID']])))
            return false;

        $aMedia = array_shift($aMediaList);

        return array('id' => $aMedia['file_id'], 'object' => $CNF['OBJECT_STORAGE']);
    }
}

/** @} */
