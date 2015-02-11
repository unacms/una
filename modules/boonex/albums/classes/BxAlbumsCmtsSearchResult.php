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

class BxAlbumsCmtsSearchResult extends BxBaseModGeneralCmtsSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
    	$this->sModule = 'bx_albums';

        parent::__construct($sMode, $aParams);

        $this->aCurrent['title'] = _t('_bx_albums_page_block_title_browse_cmts');
    }
}

/** @} */
