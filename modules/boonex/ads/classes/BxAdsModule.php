<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Ads module
 */
class BxAdsModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;

        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_CATEGORY_VIEW'],
            $CNF['FIELD_CATEGORY_SELECT']
        ));
    }

    public function actionGetCategoryForm()
    {
        if(bx_get('category') === false)
            return echoJson(array());

        return echoJson(array(
            'content' => $this->serviceGetCreatePostForm(array(
                'absolute_action_url' => true,
                'dynamic_mode' => true
            ))
        ));
    }

    public function actionInterested()
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return echoJson(array());

        $iViewer = bx_get_logged_profile_id();        
        $oViewer = BxDolProfile::getInstance($iViewer);
        if(!$oViewer)
            return echoJson(array());

        $iContentAuthor = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];
        if($iContentAuthor == $iViewer)
            return echoJson(array('msg' => _t('_bx_ads_txt_err_your_own')));

        if($this->_oDb->isInterested($iContentId, $iViewer))
            return echoJson(array('msg' => _t('_bx_ads_txt_err_duplicate')));

        if(!$this->_oDb->insertInterested(array('entry_id' => $iContentId, 'profile_id' => $iViewer)))
            return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));

        sendMailTemplate($CNF['ETEMPLATE_INTERESTED'], 0, $iContentAuthor, array(
            'viewer_name' => $oViewer->getDisplayName(),
            'viewer_url' => $oViewer->getUrl(),
            'ad_name' => $aContentInfo[$CNF['FIELD_TITLE']],
            'ad_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $iContentId
            ))
        ));

        return echoJson(array('msg' => _t('_bx_ads_txt_msg_author_notified')));
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'EntityReviews' => '',
            'EntityReviewsRating' => '',
            'CategoriesList' => '',
            'BrowseCategory' => '',
        ));
    }

    public function serviceUpdateCategoriesStats()
    {
        $aStats = $this->_oDb->getCategories(array('type' => 'collect_stats'));
        if(empty($aStats) || !is_array($aStats))
            return true;

        $iUpdated = 0;
        foreach($aStats as $aStat)
            if($this->_oDb->updateCategory(array('items' => $aStat['count']), array('id' => $aStat['id'])))
                $iUpdated++;

        return count($aStats) == $iUpdated;
    }

    public function serviceGetCategoryOptions($iParentId, $bPleaseSelect = false)
    {
        $aValues = array();
        if($bPleaseSelect)
            $aValues[] = array('key' => '', 'value' => _t('_sys_please_select'));

        $this->_getCategoryOptions($iParentId, $aValues);

        return $aValues;
    }

    public function serviceGetSearchableFields($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetSearchableFields(array_merge($aInputsAdd, $this->_getSearchableFields()));
        unset($aResult[$CNF['FIELD_CATEGORY_VIEW']], $aResult[$CNF['FIELD_CATEGORY_SELECT']]);

        return $aResult;
    }

    public function serviceGetSearchableFieldsExtended($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aInputsAdd = array_merge($aInputsAdd, $this->_getSearchableFields());

        if(isset($aInputsAdd[$CNF['FIELD_CATEGORY']])) {
            $aInputsAdd[$CNF['FIELD_CATEGORY']]['type'] = 'select';
            $aInputsAdd[$CNF['FIELD_CATEGORY']]['values_src'] = BxDolService::getSerializedService($this->_oConfig->getName(), 'get_category_options', array(0));
        }

        return parent::serviceGetSearchableFieldsExtended($aInputsAdd);
    }

    public function serviceEntityCreate ($sParams = false)
    {
        if(($sDisplay = $this->getCategoryDisplay('add')) !== false) {
            if(empty($sParams) || !is_array($sParams))
                $sParams = array();

            $sParams['display'] = $sDisplay;
        }

        return parent::serviceEntityCreate($sParams);
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads 
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-entity_reviews entity_reviews
     * 
     * @code bx_srv('bx_ads', 'entity_reviews', [...]); @endcode
     * 
     * Get reviews for particular content
     * @param $iContentId content ID
     * 
     * @see BxAdsModule::serviceEntityReviews
     */
    /** 
     * @ref bx_ads-entity_reviews "entity_reviews"
     */
    public function serviceEntityReviews($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_REVIEWS']))
            return false;

        return $this->_entityComments($CNF['OBJECT_REVIEWS'], $iContentId);
    }
    
    /**
     * @page service Service Calls
     * @section bx_ads Ads 
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-entity_reviews_rating entity_reviews_rating
     * 
     * @code bx_srv('bx_ads', 'entity_reviews_rating', [...]); @endcode
     * 
     * Get reviews rating for particular content
     * @param $iContentId content ID
     * 
     * @see BxAdsModule::serviceEntityReviewsRating
     */
    /** 
     * @ref bx_ads-entity_reviews_rating "entity_reviews_rating"
     */
    public function serviceEntityReviewsRating($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_REVIEWS']))
            return false;

        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;

        $oCmts = BxDolCmts::getObjectInstance($CNF['OBJECT_REVIEWS'], $iContentId);
        if (!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts->getRatingBlock(array('in_designbox' => false));
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads 
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-categories_list categories_list
     * 
     * @code bx_srv('bx_ads', 'categories_list', [...]); @endcode
     * 
     * Get reviews rating for particular content
     * @param $aParams additional params array, such as 'show_empty'
     * 
     * @see BxAdsModule::serviceCategoriesList
     */
    /** 
     * @ref bx_ads-categories_list "categories_list"
     */
    public function serviceCategoriesList($aParams = array())
    {
        if(!isset($aParams['show_empty']))
            $aParams['show_empty'] = true;

        return $this->_oTemplate->categoriesList($aParams);
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads 
     * @subsection bx_ads-browse Browse
     * @subsubsection bx_ads-browse_category browse_category
     * 
     * @code bx_srv('bx_ads', 'browse_category', [...]); @endcode
     * 
     * Get reviews rating for particular content
     * @param $iCategoryId category ID
     * @param $aParams additional params array, such as empty_message, ajax_paginate, etc
     * 
     * @see BxAdsModule::serviceBrowseCategory
     */
    /**
     * @ref bx_ads-browse_category "browse_category"
     */
    public function serviceBrowseCategory($iCategoryId = 0, $aParams = array())
    {
        $sParamName = $sParamGet = 'category';

        if(!$iCategoryId && bx_get($sParamGet) !== false)
            $iCategoryId = bx_process_input(bx_get($sParamGet), BX_DATA_INT);

        $bEmptyMessage = true;
        if(isset($aParams['empty_message'])) {
            $bEmptyMessage = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }

        $bAjaxPaginate = true;
        if(isset($aParams['ajax_paginate'])) {
            $bAjaxPaginate = (bool)$aParams['ajax_paginate'];
            unset($aParams['ajax_paginate']);
        }

        $aBlock = $this->_serviceBrowse ($sParamName, array_merge(array($sParamName => $iCategoryId), $aParams), BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
        if(!empty($aBlock['content'])) {
            $aCategory = $this->_oDb->getCategories(array('type' => 'id', 'id' => $iCategoryId));
            if(!empty($aCategory['title']))
                $aBlock['title'] = _t('_bx_ads_page_block_title_entries_by_category_mask', _t($aCategory['title']));
        }

        return $aBlock;
    }


    /**
     * Common methods.
     */
    public function processMetasAdd($iContentId)
    {
        if(!parent::processMetasAdd($iContentId))
            return false;

        $CNF = &$this->_oConfig->CNF;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        $aCategoryTypes = $this->_oDb->getCategoryTypes(array('type' => 'all'));
        foreach($aCategoryTypes as $aCategoryType)
            $oMetatags->metaAddAuto($iContentId, $aContentInfo, $CNF, $aCategoryType['display_add']);

        return true;
    }

    public function processMetasEdit($iContentId, $oForm)
    {
        if(!parent::processMetasEdit($iContentId, $oForm))
            return false;
        
        $CNF = &$this->_oConfig->CNF;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        
        $aCategoryTypes = $this->_oDb->getCategoryTypes(array('type' => 'all'));
        foreach($aCategoryTypes as $aCategoryType)
            $oMetatags->metaAddAuto($iContentId, $aContentInfo, $CNF, $aCategoryType['display_edit']);

        return true;
    }

    public function getCategoryDisplay($sDisplayType, $iCategory = 0)
    {
        if(empty($iCategory) && bx_get('category') !== false)
            $iCategory = (int)bx_get('category');

        if(empty($iCategory))
            return false;

        $aCategory = $this->_oDb->getCategories(array('type' => 'id_full', 'id' => $iCategory));

        $sKey = 'type_display_' . $sDisplayType;
        if(empty($aCategory[$sKey]))
            return false;

        return $aCategory[$sKey];
    }


    /**
     * Internal methods.
     */
    protected function _serviceEntityForm ($sFormMethod, $iContentId = 0, $sDisplay = false, $sCheckFunction = false, $bErrorMsg = true)
    {
        $CNF = &$this->_oConfig->CNF;

        $mixedContent = $this->_getContent($iContentId, true);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        $sDisplayType = false;
        switch($sFormMethod) {
            case 'editDataForm':
                $sDisplayType = 'edit';
                break;
            case 'viewDataForm':
            case 'viewDataEntry':
                $sDisplayType = 'view';
                break;
        }

        if($sDisplayType !== false && ($sDisplayNew = $this->getCategoryDisplay($sDisplayType, $aContentInfo[$CNF['FIELD_CATEGORY']])) !== false)
            $sDisplay = $sDisplayNew;

        return parent::_serviceEntityForm ($sFormMethod, $iContentId, $sDisplay, $sCheckFunction, $bErrorMsg);
    }

    protected function _getCategoryOptions($iParentId, &$aValues)
    {
        $aCategories = $this->_oDb->getCategories(array('type' => 'parent_id', 'parent_id' => $iParentId));
        foreach($aCategories as $aCategory) {
            $aValues[] = array('key' => $aCategory['id'], 'value' => str_repeat('--', (int)$aCategory['level']) . ' ' . _t($aCategory['title']));

            $this->_getCategoryOptions($aCategory['id'], $aValues);
        }
    }

    protected function _getSearchableFields($mixedDisplayType = '')
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($mixedDisplayType))
            $mixedDisplayType = array('add', 'edit');

        $aResult = array();
        $aDisplays = $this->_oDb->getDisplays($this->_oConfig->getName() . '_entry', $mixedDisplayType);
        foreach($aDisplays as $aDisplay) {
            if($aDisplay['display_name'] == $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'])
                continue;

            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $aDisplay['display_name'], $this->_oTemplate);
            if(!$oForm)
                continue;

            $aResult = array_merge($aResult, $oForm->aInputs);
        }

        return $aResult;
    }
    
    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $bDynamic = isset($aBrowseParams['dynamic_mode']) && (bool)$aBrowseParams['dynamic_mode'] === true;

        $sCategory = '';
        if(!empty($CNF['FIELD_CATEGORY']) && !empty($aContentInfo[$CNF['FIELD_CATEGORY']])) {
            $iCategory = (int)$aContentInfo[$CNF['FIELD_CATEGORY']];
            $aCategory = $this->_oDb->getCategories(array('type' => 'id', 'id' => $iCategory));
            $sCategory = _t($aCategory['title']);
            $sCategoryLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_CATEGORIES'], array($CNF['GET_PARAM_CATEGORY'] => $iCategory));
        }

        $sPrice = _t('_bx_ads_txt_free');
        if(!empty($CNF['FIELD_PRICE']) && !empty($aContentInfo[$CNF['FIELD_PRICE']]))
            $sPrice = _t_format_currency((float)$aContentInfo[$CNF['FIELD_PRICE']]);

        $sInclude = $this->_oTemplate->addCss(array('timeline.css'), $bDynamic);

        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);
        $aResult['text'] = $this->_oTemplate->parseHtmlByName('timeline_post_text.html', array(
            'category_link' => $sCategoryLink,
            'category_title' => $sCategory,
            'category_title_attr' => bx_html_attribute($sCategory),
            'price' => $sPrice,
            'text' => $aResult['text']
        )) . ($bDynamic ? $sInclude : '');

        
        return $aResult;
    }
}

/** @} */
