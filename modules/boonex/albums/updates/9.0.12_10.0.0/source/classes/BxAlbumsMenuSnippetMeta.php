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

class BxAlbumsMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_albums';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemItems($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iCount = $this->_oModule->_oDb->getMediaCountByContentId($this->_aContentInfo[$CNF['FIELD_ID']]);

        return $this->getUnitMetaItemText(_t($aItem['title'], $iCount));
    }
}

/** @} */
