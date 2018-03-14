<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Glossary module
 */
class BxGlsrModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    /**
     * Display terms by Alphabetical index
     * @return HTML string
     */
    public function serviceBrowseAlphabetical ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        $aIndexData = $this->_oDb->getAlphabeticalIndex();
        $aTmp = $this->_serviceBrowse('alphabetical', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
        $sRv = $this->_oTemplate->getAlphabeticalList($aIndexData, $aTmp['content']);
        $aTmp['content'] = $sRv;
        return $aTmp;
    }
}

/** @} */
