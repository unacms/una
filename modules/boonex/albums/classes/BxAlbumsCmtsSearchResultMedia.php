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

class BxAlbumsCmtsSearchResultMedia extends BxBaseModGeneralCmtsSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
    	$this->sModule = 'bx_albums';

        parent::__construct($sMode, $aParams);

        $this->aCurrent['title'] = _t('_bx_albums_page_block_title_browse_cmts_media');

        $this->sModuleObjectComments = $this->oModule->_oConfig->CNF['OBJECT_COMMENTS_MEDIA'];

        $this->aCurrent['name'] = $this->oModule->_oConfig->getName() . '_cmts_media';
        $this->aCurrent['table'] = $this->oModule->_oConfig->getDbPrefix() . 'cmts_media';

        $this->_joinTableUniqueIds();
    }
}

/** @} */
