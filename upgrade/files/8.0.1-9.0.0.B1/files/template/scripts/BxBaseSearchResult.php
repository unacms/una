<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolSearch');

class BxBaseSearchResult extends BxDolSearchResult
{
    public $isError;

    protected $sBrowseUrl; ///< currect browse url, used for paginate and other links in browsing

    protected $sUnitTemplate = 'unit.html'; ///< common template to try to use for displaying one item
    protected $sUnitTemplateLiveSearch = 'unit_live_search.html'; ///< common template to try to use for displaying live search results

    protected $aGetParams = array(); ///< get params to keep in paginate and other browsing links

    protected $iDesignBoxTemplate = 11; ///< design box ID to wrap result in, or false to disable design box wrapping

    protected $aConstants;

    protected $sCenterContentUnitSelector = false;

    protected $aContainerClasses = array('bx-search-result-block', 'bx-def-margin-bottom', 'bx-clearfix');

    protected $aUnitParams = array(); ///< additional params array to pass to unit display function

    function __construct($oFunctions = false)
    {
        parent::__construct();

        if ($oFunctions)
            $this->oFunctions = $oFunctions;
        else
            $this->oFunctions = BxTemplFunctions::getInstance();
    }

    function getMain()
    {
        // override this to return main module class
    }

    function displayResultBlock ()
    {
        $sCode = '';
        $aData = $this->getSearchData();
        if ($this->aCurrent['paginate']['num'] > 0) {

            $sCode .= $this->addCustomParts();

            foreach ($aData as $aValue)
                $sCode .= $this->displaySearchUnit($aValue);

            $sSearchResultBlockId = 'bx-search-result-block-' . rand(0, PHP_INT_MAX);
            $sClasses = implode(' ', $this->aContainerClasses);
            $sCode = '<div id="' . $sSearchResultBlockId . '" class="' . $sClasses . '">' . $sCode . '</div>';

            if (!$this->_bLiveSearch && $this->sCenterContentUnitSelector) {
                $sCode .= "
                    <script>
                        $(document).ready(function() {
                            bx_center_content('#{$sSearchResultBlockId}', '{$this->sCenterContentUnitSelector}', true);
                        });
                    </script>";
            }
        }
        return $sCode;
    }

    function displaySearchBox ($sContent, $sPaginate = '')
    {
		$sContent .= $sPaginate;
		$sMenu = $this->getDesignBoxMenu();

        if ($this->id) {
            $sTitle = _t($this->aCurrent['title']);
        	$sCode = $this->oFunctions->designBoxContent($sTitle, $sContent, $this->iDesignBoxTemplate, $sMenu);
            return '<div class="bx-page-block-container bx-search-results bx-def-padding-sec-topbottom bx-clearfix" id="bx-page-block-' . $this->id . '">' . $sCode . '</div>';
        }

        $this->addPageRssLink ();

        return array(        	
        	'content' => $sContent,
        	'menu' => $sMenu,
        );
    }

    function displaySearchUnit ($aData)
    {
        $oMain = $this->getMain();
        return $oMain->_oTemplate->unit($aData, $this->bProcessPrivateContent, $this->_bLiveSearch ? $this->sUnitTemplateLiveSearch : $this->sUnitTemplate, $this->aUnitParams);
    }

    function getDesignBoxMenu ()
    {
        return array();
    }

    protected function addPageRssLink ()
    {
        if (false === ($sLink = $this->getRssPageUrl ()))
            return;

        if (!($oTemplate = BxDolTemplate::getInstance()))
            return;

        $oTemplate->addPageRssLink($this->aCurrent['title'], $sLink);
    }

    function getRssPageUrl ()
    {
        if (!isset($this->aCurrent['rss']) || !$this->aCurrent['rss']['link'])
            return false;

        $oPermalinks = BxDolPermalinks::getInstance();
        return BX_DOL_URL_ROOT . bx_append_url_params($oPermalinks->permalink($this->aCurrent['rss']['link']), 'rss=1');
    }

    function showAdminActionsPanel($sWrapperId, $aButtons, $sCheckboxName = 'entry', $bSelectAll = true, $bSelectAllChecked = false, $sCustomHtml = '')
    {
        $aBtns = array();
        foreach ($aButtons as $k => $v) {
            if(is_array($v)) {
                $aBtns[] = $v;
                continue;
            }

            $aBtns[] = array(
                'type' => 'submit',
                'name' => $k,
                'value' => '_' == $v[0] ? _t($v) : $v,
                'onclick' => '',
            );
        }

        return BxDolTemplate::getInstance()->parseHtmlByName('adminActionsPanel.html', array(
            'bx_repeat:buttons' => $aBtns,
            'bx_if:custom_html' => array(
                'condition' => strlen($sCustomHtml) > 0,
                'content' => array(
                    'custom_html' => $sCustomHtml,
                )
            ),
            'bx_if:selectAll' => array(
                'condition' => $bSelectAll,
                'content' => array(
                    'wrapper_id' => $sWrapperId,
                    'checkbox_name' => $sCheckboxName,
                    'checked' => ($bSelectAll && $bSelectAllChecked ? 'checked="checked"' : '')
                )
            ),
        ));
    }

    function showAdminFilterPanel($sFilterValue, $sInputId = 'filter_input_id', $sCheckboxId = 'filter_checkbox_id', $sFilterName = 'filter', $sOnApply = '')
    {
        $sFilterValue = bx_html_attribute($sFilterValue);
        $isChecked = $sFilterValue ? ' checked="checked" ' : '';

        if(empty($sOnApply))
            $sOnApply = "on_filter_apply(this, '" . $sInputId . "', '" . $sFilterName . "')";

        return BxDolTemplate::getInstance()->parseHtmlByName('adminFilterPanel.html', array(
            'input_id' => $sInputId,
            'filter_value' => $sFilterValue,
            'checkbox_id' => $sCheckboxId,
            'is_checked' => $isChecked,
            'on_apply' => $sOnApply,
        ));
    }

    function showPagination($bAdmin = false, $bChangePage = true, $bPageReload = true)
    {
        $oMain = $this->getMain();

        $sPageUrl = $this->getCurrentUrl(array(), false);
        $sOnClick = $this->getCurrentOnclick(array(), false);

        $oPaginate = new BxTemplPaginate(array(
            'page_url' => $sPageUrl,
            'on_change_page' => $sOnClick,
            'num' => $this->aCurrent['paginate']['num'],
            'per_page' => $this->aCurrent['paginate']['perPage'],
            'start' => $this->aCurrent['paginate']['start'],
        ));

        return $sOnClick ? $oPaginate->getSimplePaginate() : $oPaginate->getPaginate();
    }

    /**
     * Get current browse URL with current page and additional params
     * @param $aAdditionalParams set custom additional params as key value pair
     * @param $bReplacePagesParams replace paginate params with current values or leave markers for use in paginate class
     * @return ready to use URL string with BX_DOL_URL_ROOT added in the beginning
     */
    protected function getCurrentUrl($aAdditionalParams = array(), $bReplacePagesParams = true)
    {
        if (BX_DOL_SEARCH_KEYWORD_PAGE === $this->sBrowseUrl || $this->bForceAjaxPaginate)
            return 'javascript:void(0);';

        $oPermalinks = BxDolPermalinks::getInstance();

        $sUrlStart = BX_DOL_URL_ROOT . $oPermalinks->permalink($this->sBrowseUrl);

        return $this->addAdditionalUrlParams($sUrlStart, $aAdditionalParams, $bReplacePagesParams);
    }

    /**
     * Get current browse URL with current page and additional params
     * @param $aAdditionalParams set custom additional params as key value pair
     * @param $bReplacePagesParams replace paginate params with current values or leave markers for use in paginate class
     * @return ready to use URL string with BX_DOL_URL_ROOT added in the beginning
     */
    protected function getCurrentOnclick($aAdditionalParams = array(), $bReplacePagesParams = true)
    {
        if (BX_DOL_SEARCH_KEYWORD_PAGE !== $this->sBrowseUrl && !$this->bForceAjaxPaginate)
            return '';

        if (BX_DOL_SEARCH_KEYWORD_PAGE === $this->sBrowseUrl) {

            $sLoadDynamicUrl = BX_DOL_URL_ROOT . 'searchKeywordContent.php?searchMode=ajax&section[]=' . $this->aCurrent['name'];
            $sLoadDynamicUrl = $this->addAdditionalUrlParams($sLoadDynamicUrl, $aAdditionalParams, $bReplacePagesParams);

            $sKeyword = bx_get('keyword');
            if ($sKeyword !== false && mb_strlen($sKeyword) > 0)
                $sLoadDynamicUrl = bx_append_url_params($sLoadDynamicUrl, 'keyword=' . rawurlencode($sKeyword));

            return "return !loadDynamicBlockAuto(this, '{$sLoadDynamicUrl}');";

        } else {
            
            return "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}', " . (empty($aAdditionalParams) ? 'undefined' : "'" . trim($this->addAdditionalUrlParams('', $aAdditionalParams, false, false), '&?') . "'") . ");"; 

        }
    }

    protected function addAdditionalUrlParams($sUrl, $aAdditionalParams, $bReplacePagesParams, $bAddPaginateParams = true)
    {
        if ($bAddPaginateParams) { // add pages params
            $sUrl = bx_append_url_params($sUrl, array (
                'type' => $this->_sMetaType,
                'start' => $bReplacePagesParams ? (int)$this->aCurrent['paginate']['start'] : '{start}',
                'per_page' => $bReplacePagesParams ? (int)$this->aCurrent['paginate']['perPage'] : '{per_page}',
            ));
        }

        foreach ($this->aGetParams as $sGetParam) {
            $sValue = false;
            if (isset($aAdditionalParams[$sGetParam]))
                $sValue = $aAdditionalParams[$sGetParam];
            elseif (false !== bx_get($sGetParam))
                $sValue = bx_get($sGetParam);
            if (false !== $sValue)
                $sUrl = bx_append_url_params($sUrl, $sGetParam . '=' . rawurlencode($sValue));
        }

        return $sUrl;
    }

    function clearFilters ($aPassParams = array(), $aPassJoins = array())
    {
        //clear sorting
        $this->aCurrent['sorting'] = 'last';

        //clear restrictions
        foreach ($this->aCurrent['restriction'] as $sKey => $aValue) {
            if (!in_array($sKey, $aPassParams))
                $this->aCurrent['restriction'][$sKey]['value'] = '';
        }

        //clear unnecessary joins (remains only profile join)
        $aPassJoins[] = 'profile';
        $aTemp = array();
        foreach ($aPassJoins as $sValue) {
            if (isset($this->aCurrent['join'][$sValue]) && is_array($this->aCurrent['join'][$sValue]))
                $aTemp[$sValue] = $this->aCurrent['join'][$sValue];
        }
        $this->aCurrent['join'] = $aTemp;
    }

    function fillFilters ($aParams)
    {
        // transform all given values to fields values
        if (is_array($aParams)) {
            foreach ($aParams as $sKey => $mixedValue) {
                if (isset($this->aCurrent['restriction'][$sKey]))
                    $this->aCurrent['restriction'][$sKey]['value'] = $mixedValue;
            }
        }
    }

    /**
     * Set design box template id to use to wrap search results in
     */
    function setDesignBoxTemplateId ($i)
    {
        $this->iDesignBoxTemplate = $i;
    }

    /**
     * Set unit class selector for content centering 
     */
    function setCenterContentUnitSelector ($s)
    {
        $this->sCenterContentUnitSelector = $s;
    }

    /**
     * Add class to search result container 
     * @param $mixed CSS class name string or array of classes
     */
    function addContainerClass ($mixed)
    {
        if (!is_array($mixed))
            $mixed = array($mixed);

        foreach ($mixed as $s)
            if (false === array_search($s, $this->aContainerClasses))
                $this->aContainerClasses[] = $s;
    }

    /**
     * Remove class from search result container 
     * @param $mixed CSS class name string or array of classes
     */
    function removeContainerClass ($mixed)
    {
        if (!is_array($mixed))
            $mixed = array($mixed);

        foreach ($mixed as $s)
            if (false !== ($i = array_search($s, $this->aContainerClasses)))
                unset($this->aContainerClasses[$i]);
    }

    public function setUnitParams ($aParamsAdd = array(), $aParamsRemove = array())
    {
        if ($aParamsAdd)
            $this->aUnitParams = array_merge($this->aUnitParams, $aParamsAdd);
        if ($aParamsRemove)
            $this->aUnitParams = array_diff_key($this->aUnitParams, $aParamsRemove);
    }
}

/** @} */
