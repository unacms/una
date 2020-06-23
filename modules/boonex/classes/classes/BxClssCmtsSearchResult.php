<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

class BxClssCmtsSearchResult extends BxBaseModGeneralCmtsSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
    	$this->sModule = 'bx_classes';

        parent::__construct($sMode, $aParams);

        $this->aCurrent['title'] = _t('_bx_classes_page_block_title_browse_cmts');
    }
}

/** @} */
