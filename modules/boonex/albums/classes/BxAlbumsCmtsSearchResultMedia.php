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
    }
}

/** @} */
