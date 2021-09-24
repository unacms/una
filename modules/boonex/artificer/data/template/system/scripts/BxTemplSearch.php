<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplSearch extends BxBaseSearch
{
    public function __construct ($aChoice = '', $oTemplate = false)
    {
        parent::__construct ($aChoice, $oTemplate);
        
        $this->_sSearchFunctionParams .= ", bx_site_search_complete";
    }

    public function setLiveSearch($bLiveSearch)
    {
        parent::setLiveSearch($bLiveSearch);

        if($this->_bLiveSearch) 
            $this->_sIdLoading = $this->_sIdForm;
    }

    public function getResultsContainer($sCode = '')
    {
        $sResult = parent::getResultsContainer($sCode);

        return $this->_oTemplate->parseHtmlByContent($sResult, [
            'bx_if:show_hidden' => [
                'condition' => empty($sCode),
                'content' => []
            ]
        ]);
    }
}

/** @} */
