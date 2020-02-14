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

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_STATUS_ADMIN']
        ));
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'BrowseAlphabetical' => '',
        ));
    }

    /**
     * @page service Service Calls
     * @section bx_glossary Glossary
     * @subsection bx_glossary-browse Browse
     * @subsubsection bx_glossary-browse_alphabetical browse_alphabetical
     * 
     * @code bx_srv('bx_glossary', 'browse_alphabetical', [...]); @endcode
     * 
     * Display terms by alphabetical index
     * 
     * @param $sUnitView unit view
     * @param $bDisplayEmptyMsg show "empty" message or not if nothing found
     * @param $bAjaxPaginate use AJAX paginate or not
     *
     * @see BxEventsModule::serviceBrowseAlphabetical
     */
    /** 
     * @ref bx_glossary-browse_alphabetical "browse_alphabetical"
     */
    public function serviceBrowseAlphabetical ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        $aTmp = $this->_serviceBrowse('alphabetical', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
        $iPerPage = getParam('bx_glossary_per_page_browse');   
        $aLetterData = $this->_oDb->getAlphabeticalIndex();
        
        $iStartPage = bx_get('start') ? bx_get('start') : 0;
        foreach ($aLetterData as $key => $aLetter){
            $iStart = floor($aLetter['row_number'] / $iPerPage) * $iPerPage;
            if ($iStartPage == $iStart){
                $aLetterData[$key]['url'] = "javascript:BxGlsrAlphabeticalList_goAnchor('" . $aLetter['letter'] . "')";
            }
            else{
                $aBaseLink = bx_get('dynamic') ? parse_url($_SERVER['HTTP_REFERER']) : parse_url($_SERVER['REQUEST_URI']);
                $aLetterData[$key]['url'] = $aBaseLink['path'] . ($iStart>0 ? '?start=' . $iStart  . '&per_page=' . $iPerPage . '&letter=' . $aLetter['letter'] :  '?letter=' . $aLetter['letter']); 
            }
        }
        $sRv = $this->_oTemplate->getAlphabeticalList($aLetterData, $aTmp['content']);
        $aTmp['content'] = $sRv;
        return $aTmp;
    }
}

/** @} */
