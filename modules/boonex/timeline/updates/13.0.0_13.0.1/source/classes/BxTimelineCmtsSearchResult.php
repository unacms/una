<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineCmtsSearchResult extends BxBaseModGeneralCmtsSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
    	$this->sModule = 'bx_timeline';

        parent::__construct($sMode, $aParams);

        $this->aCurrent['title'] = _t('_bx_timeline_page_block_title_browse_cmts');
        $this->aCurrent['table'] = $this->oModule->_oConfig->getDbPrefix() . 'comments';

        $this->_joinTableUniqueIds();
    }
}

/** @} */
