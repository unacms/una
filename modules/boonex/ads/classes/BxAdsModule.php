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

define('BX_ADS_STATUS_OFFER', 'offer');
define('BX_ADS_STATUS_SOLD', 'sold');

define('BX_ADS_OFFER_STATUS_ACCEPTED', 'accepted');
define('BX_ADS_OFFER_STATUS_AWAITING', 'awaiting');
define('BX_ADS_OFFER_STATUS_DECLINED', 'declined');
define('BX_ADS_OFFER_STATUS_CANCELED', 'canceled');

/**
 * Ads module
 */
class BxAdsModule extends BxBaseModTextModule
{
    protected $_aOfferStatuses;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);

        $CNF = &$this->_oConfig->CNF;

        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_CATEGORY_VIEW'],
            $CNF['FIELD_CATEGORY_SELECT']
        ));

        $this->_aOfferStatuses = array(
            BX_ADS_OFFER_STATUS_ACCEPTED,
            BX_ADS_OFFER_STATUS_AWAITING,
            BX_ADS_OFFER_STATUS_DECLINED
        );
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

    public function actionCheckName()
    {
        $CNF = &$this->_oConfig->CNF;

        $iId = (int)bx_get($CNF['FIELD_ID']);
    	$sTitle = bx_process_input(bx_get($CNF['FIELD_TITLE']));

    	echoJson($this->serviceCheckName($sTitle, $iId));
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

        $iInterestId = $this->_oDb->insertInterested(array('entry_id' => $iContentId, 'profile_id' => $iViewer));
        if(!$iInterestId)
            return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));

        bx_alert($this->getName(), 'doInterest', $iContentId, $iViewer, array(
            'subobject_id' => $iInterestId, 
            'subobject_author_id' => $iViewer, 

            'object_author_id' => $iContentAuthor
        ));

        if(getParam($CNF['PARAM_USE_IIN']) == 'on')
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

    public function actionShow()
    {
        return echoJson($this->_actionChangeStatus(BX_BASE_MOD_TEXT_STATUS_ACTIVE));
    }

    public function actionHide()
    {
        return echoJson($this->_actionChangeStatus(BX_BASE_MOD_TEXT_STATUS_HIDDEN));
    }

    public function actionMakeOffer()
    {
        $CNF = &$this->_oConfig->CNF;
        $sJsObject = $this->_oConfig->getJsObject('entry');

        $iAuthorId = bx_get_logged_profile_id();
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(empty($iAuthorId))
            return echoJson(array('code' => 1, 'eval' => 'window.open("' . BxDolPermalinks::getInstance()->permalink('page.php?i=login') . '", "_self");'));

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return echoJson(array());

        $aOffer = $this->_oDb->getOffersBy(array(
            'type' => 'content_and_author_ids', 
            'content_id' => $iContentId, 
            'author_id' => $iAuthorId, 
            'status' => BX_ADS_OFFER_STATUS_AWAITING
        ));

        if(!empty($aOffer) && is_array($aOffer))
            return echoJson(array('code' => 2, 'msg' => _t('_bx_ads_txt_err_duplicate')));

        $aOffer = $this->_oDb->getOffersBy(array(
            'type' => 'content_and_author_ids', 
            'content_id' => $iContentId, 
            'author_id' => $iAuthorId, 
            'status' => BX_ADS_OFFER_STATUS_ACCEPTED
        ));

        if(!empty($aOffer) && is_array($aOffer))
            return echoJson(array('code' => 3, 'msg' => _t('_bx_ads_txt_err_offer_accepted')));

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_OFFER'], $CNF['OBJECT_FORM_OFFER_DISPLAY_ADD']);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . bx_append_url_params($this->_oConfig->getBaseUri() . 'make_offer', array('id' => $iContentId));
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aValToAdd = array('content_id' => $iContentId, 'author_id' => $iAuthorId);

            $iId = (int)$oForm->insert($aValToAdd);
            if($iId != 0)
                $aResult = array('code' => 0, 'msg' => _t('_bx_ads_txt_msg_offer_added'), 'eval' => $sJsObject . '.onMakeOffer(oData);', 'id' => $iId);
            else
                $aResult = array('code' => 4, 'msg' => _t('_bx_ads_txt_err_cannot_perform_action'));

            $this->onOfferAdded($iId, $aResult);

            return echoJson($aResult);
        }

        $sContent = BxTemplFunctions::getInstance()->transBox($this->_oConfig->getHtmlIds('offer_popup'), $this->_oTemplate->parseHtmlByName('offer_popup.html', array(
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true)
        )));

        return echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false, 'removeOnClose' => true))));
    }

    public function actionViewOffer()
    {
        $CNF = &$this->_oConfig->CNF;

        $iAuthorId = bx_get_logged_profile_id();
        $iOfferId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $aOffer = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iOfferId));
        if(empty($aOffer) || !is_array($aOffer))
            return echoJson(array());
        
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_OFFER'], $CNF['OBJECT_FORM_OFFER_DISPLAY_VIEW']);
        $oForm->initChecker($aOffer);

        $sContent = BxTemplFunctions::getInstance()->transBox($this->_oConfig->getHtmlIds('offer_popup'), $this->_oTemplate->parseHtmlByName('offer_popup.html', array(
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true)
        )));

        return echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => true, 'removeOnClose' => true))));
    }

    public function actionAcceptOffer()
    {
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$this->offerAccept($iId))
            return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));

        return echoJson(array('msg' => _t('_bx_ads_txt_msg_offer_accepted'), 'reload' => 1));
    }

    public function actionDeclineOffer()
    {
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$this->offerDecline($iId))
            return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));

        return echoJson(array('msg' => _t('_bx_ads_txt_msg_offer_declined'), 'reload' => 1));
    }
            
    public function actionCancelOffer()
    {
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$this->offerCancel($iId))
            return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));     

        return echoJson(array('msg' => _t('_bx_ads_txt_msg_offer_canceled'), 'reload' => 1));
    }
    
    public function actionPayOffer()
    {
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(($mixedResult = $this->offerPay($iId)) !== false)
            return echoJson($mixedResult);

        return echoJson(array('msg' => _t('_bx_ads_txt_err_cannot_perform_action')));     
    }

    public function actionShipped()
    {
        return echoJson($this->_actionMarkAs('shipped'));
    }

    public function actionReceived()
    {
        return echoJson($this->_actionMarkAs('received'));
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

    public function serviceCheckName($sTitle, $iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

    	if(empty($sTitle))
            return array();

        $sName = '';
        if(!empty($iId)) {
            $aEntry = $this->_oDb->getContentInfoById($iId); 
            if(strcmp($sTitle, $aEntry[$CNF['FIELD_NAME']]) == 0) 
                $sName = $sTitle;
        }

        return array(
            'title' => $sTitle,
            'name' => !empty($sName) ? $sName : $this->_oConfig->getEntryName($sTitle, $iId)
    	);
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
        unset($aResult[$CNF['FIELD_NOTES_PURCHASED']]);
        unset($aResult[$CNF['FIELD_CATEGORY_VIEW']], $aResult[$CNF['FIELD_CATEGORY_SELECT']]);
        unset($aResult[$CNF['FIELD_PRICE']], $aResult[$CNF['FIELD_YEAR']]);

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

        if(isset($aInputsAdd[$CNF['FIELD_PRICE']])) {
            $aInputsAdd[$CNF['FIELD_PRICE']]['search_type'] = 'text_range';
            $aInputsAdd[$CNF['FIELD_PRICE']]['search_operator'] = 'between';
        }

        if(isset($aInputsAdd[$CNF['FIELD_YEAR']])) {
            $aInputsAdd[$CNF['FIELD_YEAR']]['search_type'] = 'text_range';
            $aInputsAdd[$CNF['FIELD_YEAR']]['search_operator'] = 'between';
        }

        return parent::serviceGetSearchableFieldsExtended($aInputsAdd);
    }

    /**
     * Mark an ad as shipped/received by seller/bauer accordingly.
     * @param type $sAction - shipped/received action.
     * @param type $iContentId - an ad the action will be performed with.
     * @param integer $iProfileSrc - profile, who performed the action.
     * @param integer $iProfileDst - profile, the action is pointed on.
     * @return boolean - true or a string value with error message.
     */
    public function serviceMarkAs($sAction, $iContentId, $iProfileSrc = 0, $iProfileDst = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileSrc)
            $iProfileSrc = bx_get_logged_profile_id();

        $sTxtError = '_bx_ads_txt_err_cannot_perform_action';
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        $sMethodCheck = 'checkAllowedMark' . bx_gen_method_name($sAction) . 'ForProfile';
        if(!method_exists($this, $sMethodCheck))
            return _t($sTxtError);

        if(($mixedCheckResult = $this->$sMethodCheck($aContentInfo, false, $iProfileSrc)) !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedCheckResult;

        if((int)$this->_oDb->updateEntriesBy(array($CNF['FIELD_' . strtoupper($sAction)] => time()), array($CNF['FIELD_ID'] => $iContentId)) == 0)
            return _t($sTxtError);

        $this->$sMethodCheck($aContentInfo, true, $iProfileSrc);

        $sMethodOnResult = 'on' . bx_gen_method_name($sAction);
        if(method_exists($this, $sMethodOnResult))
            $this->$sMethodOnResult($aContentInfo, $iProfileSrc, $iProfileDst);        

        return true;
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

    public function serviceGetNotificationsData()
    {
        $sModule = $this->_aModule['name'];

        $sEventPrivacy = $sModule . '_allow_view_event_to';
        if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
            $sEventPrivacy = '';

        $aResult = parent::serviceGetNotificationsData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_interest', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doInterest', 'module_name' => $sModule, 'module_method' => 'get_notifications_interest', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy)
        ));

        $aResult['settings'] = array_merge($aResult['settings'], array(
            array('group' => 'interest', 'unit' => $sModule, 'action' => 'doInterest', 'types' => array('personal'))
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'doInterest')
        ));

        return $aResult; 
    }
    
    public function serviceGetNotificationsInterest($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
    	$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $iInterestedId = (int)$aEvent['subobject_id'];
        $aInterestedInfo = $this->_oDb->getInterested(array('type' => 'id', 'id' => $iInterestedId));
        if(empty($aInterestedInfo) || !is_array($aInterestedInfo))
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId);

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $aContentInfo[$CNF['FIELD_TITLE']],
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'subentry_sample' => $CNF['T']['txt_sample_interest_single'],
            'subentry_url' => '',
            'lang_key' => '_bx_ads_txt_ntfs_subobject_interested', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-block_licenses block_licenses
     * 
     * @code bx_srv('bx_ads', 'block_licenses', [...]); @endcode
     * 
     * Get page block with a list of licenses purchased by currently logged member.
     *
     * @return an array describing a block to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxAdsModule::serviceBlockLicenses
     */
    /** 
     * @ref bx_ads-block_licenses "block_licenses"
     */
    public function serviceBlockLicenses() 
    {
        return $this->_getBlockLicenses();
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-block_licenses_administration block_licenses_administration
     * 
     * @code bx_srv('bx_ads', 'block_licenses_administration', [...]); @endcode
     * 
     * Get page block with a list of all licenses. It's needed for moderators/administrators.
     *
     * @return an array describing a block to display on the site or false if there is no enough input data. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxAdsModule::serviceBlockLicensesAdministration
     */
    /** 
     * @ref bx_ads-block_licenses "block_licenses"
     */
    public function serviceBlockLicensesAdministration() 
    {
        return $this->_getBlockLicenses('administration');
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-page_blocks Page Blocks
     * @subsubsection bx_ads-block_licenses_note block_licenses_note
     * 
     * @code bx_srv('bx_ads', 'block_licenses_note', [...]); @endcode
     * 
     * Get page block with a notice for licenses usage.
     *
     * @return HTML string with block content to display on the site.
     * 
     * @see BxAdsModule::serviceBlockLicensesNote
     */
    /** 
     * @ref bx_ads-block_licenses_note "block_licenses_note"
     */
    public function serviceBlockLicensesNote()
    {
        return MsgBox(_t('_bx_ads_page_block_content_licenses_note'));
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-get_payment_data get_payment_data
     * 
     * @code bx_srv('bx_ads', 'get_payment_data', [...]); @endcode
     * 
     * Get an array with module's description. Is needed for payments processing module.
     * 
     * @return an array with module's description.
     * 
     * @see BxAdsModule::serviceGetPaymentData
     */
    /** 
     * @ref bx_ads-get_payment_data "get_payment_data"
     */
    public function serviceGetPaymentData()
    {
        $CNF = &$this->_oConfig->CNF;

        $oPermalink = BxDolPermalinks::getInstance();

        $aResult = $this->_aModule;
        $aResult['url_browse_order_common'] = BX_DOL_URL_ROOT . $oPermalink->permalink($CNF['URL_LICENSES_COMMON'], array('filter' => '{order}'));
        $aResult['url_browse_order_administration'] = BX_DOL_URL_ROOT . $oPermalink->permalink($CNF['URL_LICENSES_ADMINISTRATION'], array('filter' => '{order}'));

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-get_cart_item get_cart_item
     * 
     * @code bx_srv('bx_ads', 'get_cart_item', [...]); @endcode
     * 
     * Get an array with prodict's description. Is used in Shopping Cart in payments processing module.
     * 
     * @param $mixedItemId product's ID or Unique Name.
     * @return an array with prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAdsModule::serviceGetCartItem
     */
    /** 
     * @ref bx_ads-get_cart_item "get_cart_item"
     */
    public function serviceGetCartItem($mixedItemId)
    {
    	$CNF = &$this->_oConfig->CNF;

        if(!$mixedItemId)
            return array();

        if(is_numeric($mixedItemId))
            $aItem = $this->_oDb->getContentInfoById((int)$mixedItemId);
        else
            $aItem = $this->_oDb->getContentInfoByName($mixedItemId);

        if(empty($aItem) || !is_array($aItem))
            return array();

        $iItemId = (int)$aItem[$CNF['FIELD_ID']];
        $fItemPrice = (float)$aItem[$CNF['FIELD_PRICE']];

        if($this->isAuction($aItem)) {
            $iUserId = bx_get_logged_profile_id();

            $aOffer = $this->_oDb->getOffersBy(array(
                'type' => 'content_and_author_ids', 
                'content_id' => $iItemId, 
                'author_id' => $iUserId,
                'status' => BX_ADS_OFFER_STATUS_ACCEPTED
            ));

            if(!empty($aOffer) && is_array($aOffer))
                $fItemPrice = (float)$aOffer[$CNF['FIELD_OFR_AMOUNT']];
        }

        return array (
            'id' => $iItemId,
            'author_id' => $aItem[$CNF['FIELD_AUTHOR']],
            'name' => $aItem[$CNF['FIELD_NAME']],
            'title' => $aItem[$CNF['FIELD_TITLE']],
            'description' => $aItem[$CNF['FIELD_TEXT']],
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'], array('id' => $aItem[$CNF['FIELD_ID']])),
            'price_single' => $fItemPrice,
            'price_recurring' => '',
            'period_recurring' => 0,
            'period_unit_recurring' => '',
            'trial_recurring' => ''
        );
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-get_cart_items get_cart_items
     * 
     * @code bx_srv('bx_ads', 'get_cart_items', [...]); @endcode
     * 
     * Get an array with prodicts' descriptions by seller. Is used in Manual Order Processing in payments processing module.
     * 
     * @param $iSellerId seller ID.
     * @return an array with prodicts' descriptions. Empty array is returned if something is wrong or seller doesn't have any products.
     * 
     * @see BxAdsModule::serviceGetCartItems
     */
    /** 
     * @ref bx_ads-get_cart_items "get_cart_items"
     */
    public function serviceGetCartItems($iSellerId)
    {
    	$CNF = &$this->_oConfig->CNF;

        $iSellerId = (int)$iSellerId;
        if(empty($iSellerId))
            return array();

        $aItems = $this->_oDb->getEntriesByAuthor($iSellerId);

        $aResult = array();
        foreach($aItems as $aItem)
            $aResult[] = array(
                'id' => $aItem[$CNF['FIELD_ID']],
                'author_id' => $aItem[$CNF['FIELD_AUTHOR']],
                'name' => $aItem[$CNF['FIELD_NAME']],
                'title' => $aItem[$CNF['FIELD_TITLE']],
                'description' => $aItem[$CNF['FIELD_TEXT']],
                'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'], array('id' => $aItem[$CNF['FIELD_ID']])),
                'price_single' => $aItem[$CNF['FIELD_PRICE_SINGLE']],
                'price_recurring' => '',
                'period_recurring' => 0,
                'period_unit_recurring' => '',
                'trial_recurring' => ''
            );

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-register_cart_item register_cart_item
     * 
     * @code bx_srv('bx_ads', 'register_cart_item', [...]); @endcode
     * 
     * Register a processed single time payment inside the Ads module. Is called with payment processing module after the payment was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with purchased prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAdsModule::serviceRegisterCartItem
     */
    /** 
     * @ref bx_ads-register_cart_item "register_cart_item"
     */
    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aItem = $this->serviceGetCartItem($iItemId);
        if(empty($aItem) || !is_array($aItem))
            return array();

        $aEntry = $this->_oDb->getContentInfoById($iItemId);
        $iEntryQnt = (int)$aEntry[$CNF['FIELD_QUANTITY']];
        if(empty($aEntry) || !is_array($aEntry) || ($iEntryQnt - $iItemCount) < 0)
            return array();

        if(!$this->_oDb->registerLicense($iClientId, $iItemId, $iItemCount, $sOrder, $sLicense))
            return array();

        $iEntryQnt -= $iItemCount;
        $this->_oDb->updateEntriesBy(array(
            $CNF['FIELD_QUANTITY'] => $iEntryQnt,
            $CNF['FIELD_STATUS'] => $iEntryQnt == 0 ? BX_ADS_STATUS_SOLD : BX_BASE_MOD_TEXT_STATUS_ACTIVE,
            $CNF['FIELD_SOLD'] => $iEntryQnt == 0 ? time() : 0,
        ), array($CNF['FIELD_ID'] => $iItemId));

        bx_alert($this->getName(), 'license_register', 0, false, array(
            'product_id' => $iItemId,
            'profile_id' => $iClientId,
            'order' => $sOrder,
            'license' => $sLicense,
            'count' => $iItemCount
        ));

        if($iEntryQnt == 0) {
            $aParams = $this->_alertParams($aEntry);
            bx_alert($this->getName(), 'sold', $iItemId, false, $aParams);
        }

        $oClient = BxDolProfile::getInstanceMagic($iClientId);
        $oSeller = BxDolProfile::getInstanceMagic($iSellerId);
        $sSellerUrl = $oSeller->getUrl();
        $sSellerName = $oSeller->getDisplayName();

        $sNote = $aEntry[$CNF['FIELD_NOTES_PURCHASED']];
        if(empty($sNote))
            $sNote = _t('_bx_ads_txt_purchased_note', $sSellerUrl, $sSellerName);

        sendMailTemplate($CNF['ETEMPLATE_PURCHASED'], 0, $iClientId, array(
            'client_name' => $oClient->getDisplayName(),
            'entry_name' => $aEntry[$CNF['FIELD_TITLE']],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'], array('id' => $aEntry[$CNF['FIELD_ID']])),
            'vendor_url' => $sSellerUrl,
            'vendor_name' => $sSellerName,
            'count' => (int)$iItemCount,
            'license' => $sLicense,
            'notes' => $sNote,
        ));

        return $aItem;
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-register_subscription_item register_subscription_item
     * 
     * @code bx_srv('bx_ads', 'register_subscription_item', [...]); @endcode
     * 
     * Register a processed subscription (recurring payment) inside the Ads module. Is called with payment processing module after the subscription was registered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return an array with subscribed prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAdsModule::serviceRegisterSubscriptionItem
     */
    /** 
     * @ref bx_ads-register_subscription_item "register_subscription_item"
     */
    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return array();
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-reregister_cart_item reregister_cart_item
     * 
     * @code bx_srv('bx_ads', 'reregister_cart_item', [...]); @endcode
     * 
     * Reregister a single time payment inside the Ads module. Is called with payment processing module after the payment was reregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemIdOld old item ID.
     * @param $iItemIdNew new item ID.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @return an array with purchased prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAdsModule::serviceReregisterCartItem
     */
    /** 
     * @ref bx_ads-reregister_cart_item "reregister_cart_item"
     */
    public function serviceReregisterCartItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder)
    {
        return array();
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-reregister_subscription_item reregister_subscription_item
     * 
     * @code bx_srv('bx_ads', 'reregister_subscription_item', [...]); @endcode
     * 
     * Reregister a subscription (recurring payment) inside the Ads module. Is called with payment processing module after the subscription was reregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemIdOld old item ID.
     * @param $iItemIdNew new item ID.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @return an array with subscribed prodict's description. Empty array is returned if something is wrong.
     * 
     * @see BxAdsModule::serviceReregisterSubscriptionItem
     */
    /** 
     * @ref bx_ads-reregister_subscription_item "reregister_subscription_item"
     */
    public function serviceReregisterSubscriptionItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder)
    {
        return array();
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-unregister_cart_item unregister_cart_item
     * 
     * @code bx_srv('bx_ads', 'unregister_cart_item', [...]); @endcode
     * 
     * Unregister an earlier processed single time payment inside the Ads module. Is called with payment processing module after the payment was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the payment was unregistered or not.
     * 
     * @see BxAdsModule::serviceUnregisterCartItem
     */
    /** 
     * @ref bx_ads-unregister_cart_item "unregister_cart_item"
     */
    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        if(!$this->_oDb->unregisterLicense($iClientId, $iItemId, $sOrder, $sLicense))
            return false;

        bx_alert($this->getName(), 'license_unregister', 0, false, array(
            'product_id' => $iItemId,
            'profile_id' => $iClientId,
            'order' => $sOrder,
            'license' => $sLicense,
            'count' => $iItemCount
        ));

    	return true;;
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-unregister_subscription_item unregister_subscription_item
     * 
     * @code bx_srv('bx_ads', 'unregister_subscription_item', [...]); @endcode
     * 
     * Unregister an earlier processed subscription (recurring payment) inside the Ads module. Is called with payment processing module after the subscription was unregistered there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @param $sLicense license number genereted with payment processing module for internal usage
     * @return boolean value determining where the subscription was unregistered or not.
     * 
     * @see BxAdsModule::serviceUnregisterSubscriptionItem
     */
    /** 
     * @ref bx_ads-unregister_subscription_item "unregister_subscription_item"
     */
    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return true; 
    }

    /**
     * @page service Service Calls
     * @section bx_ads Ads
     * @subsection bx_ads-payments Payments
     * @subsubsection bx_ads-cancel_subscription_item cancel_subscription_item
     * 
     * @code bx_srv('bx_ads', 'cancel_subscription_item', [...]); @endcode
     * 
     * Cancel an earlier processed subscription (recurring payment) inside the Ads module. Is called with payment processing module after the subscription was canceled there.
     * 
     * @param $iClientId client ID.
     * @param $iSellerId seller ID
     * @param $iItemId item ID.
     * @param $iItemCount the number of purchased items.
     * @param $sOrder order number received from payment provider (PayPal, Stripe, etc)
     * @return boolean value determining where the subscription was canceled or not.
     * 
     * @see BxAdsModule::serviceCancelSubscriptionItem
     */
    /** 
     * @ref bx_ads-cancel_subscription_item "cancel_subscription_item"
     */
    public function serviceCancelSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
    	return true;
    }

    public function serviceGetOffersCount($sStatus = '')
    {
        if(!$this->_oConfig->isAuction())
            return 0;

        if(empty($sStatus) || !in_array($sStatus, $this->_aOfferStatuses))
            $sStatus = BX_ADS_OFFER_STATUS_AWAITING;

        $iUserId = bx_get_logged_profile_id();
        return $this->_oDb->getOffersBy(array('type' => 'content_author_id', 'author_id' => $iUserId, 'status' => $sStatus, 'count' => true));
    }

    public function serviceGetLiveUpdates($sStatus, $aMenuItemParent, $aMenuItemChild, $iCount = 0)
    {
        if(!in_array($sStatus, $this->_aOfferStatuses))
            return false;

        $iUserId = bx_get_logged_profile_id();
        $iCountNew = $this->_oDb->getOffersBy(array('type' => 'content_author_id', 'author_id' => $iUserId, 'status' => $sStatus, 'count' => true));
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => 'bx_menu_show_live_update(oData)', // required
            'data' => array(
                'code' => BxDolTemplate::getInstance()->parseHtmlByTemplateName('menu_item_addon', array(
                    'content' => '{count}'
                )),
            'mi_parent' => $aMenuItemParent,
            'mi_child' => $aMenuItemChild
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
    	);
    }

    public function serviceOffers()       
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isAuction())
            return '';

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_OFFERS_ALL']);
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }

    public function serviceEntityOffers($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isAuction())
            return '';

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_OFFERS']);
        if(!$oGrid)
            return '';

        if(empty($iContentId) && ($_iContentId = bx_get('id')) !== false)
            $iContentId = (int)$_iContentId;

        $oGrid->setContentId($iContentId);
        return $oGrid->getCode();
    }

    public function serviceEntityOfferAccepted($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oConfig->isAuction())
            return '';

        $iUserId = bx_get_logged_profile_id();

        if(empty($iContentId) && ($_iContentId = bx_get('id')) !== false)
            $iContentId = (int)$_iContentId;

        $aContent = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContent) || !is_array($aContent) || $aContent[$CNF['FIELD_QUANTITY']] <= 0)
            return '';

        $aOffer = $this->_oDb->getOffersBy(array(
            'type' => 'content_and_author_ids', 
            'content_id' => $iContentId, 
            'author_id' => $iUserId,
            'status' => BX_ADS_OFFER_STATUS_ACCEPTED
        ));

        if(empty($aOffer) || !is_array($aOffer))
            return '';

        return $this->_oTemplate->entryOfferAccepted($iUserId, $aContent, $aOffer);
    }

    public function isEntryActive($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        if($aContentInfo[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id() || $this->_isModerator())
            return true;

        if(isset($CNF['FIELD_STATUS']) && !in_array($aContentInfo[$CNF['FIELD_STATUS']], array(BX_BASE_MOD_TEXT_STATUS_ACTIVE, BX_ADS_STATUS_OFFER, BX_ADS_STATUS_SOLD)))
            return false;

        if(isset($CNF['FIELD_STATUS_ADMIN']) && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != 'active')
            return false;

        return true;        
    }

    public function isAuction($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        return $this->_oConfig->isAuction() && (int)$aContentInfo[$CNF['FIELD_AUCTION']] != 0;
    }

    public function isAllowedMakeOffer($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedMakeOffer($mixedContent, $isPerformAction) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedMakeOffer($mixedContent, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $sTxtError = '_sys_txt_access_denied';

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        if(empty($mixedContent) || !is_array($mixedContent))
            return _t($sTxtError);

        if(!$this->isAuction($mixedContent) || (int)$mixedContent[$CNF['FIELD_QUANTITY']] <= 0)
            return _t($sTxtError);

        if($mixedContent[$CNF['FIELD_AUTHOR']] != bx_get_logged_profile_id())
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t($sTxtError);
    }

    public function isAllowedViewOffers($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedViewOffers($mixedContent, $isPerformAction) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedViewOffers($mixedContent, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $sTxtError = '_sys_txt_access_denied';

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        if(empty($mixedContent) || !is_array($mixedContent))
            return _t($sTxtError);

        if(!$this->isAuction($mixedContent))
            return _t($sTxtError);

        if($this->_isModerator())
            return CHECK_ACTION_RESULT_ALLOWED;

        if($mixedContent[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id())
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t($sTxtError);
    }

    public function isAllowedMarkShipped($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedMarkShipped($mixedContent, $isPerformAction) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedMarkShipped($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedMarkShippedForProfile($mixedContent, $isPerformAction);
    }

    public function checkAllowedMarkShippedForProfile($mixedContent, $isPerformAction = false, $iProfileId = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $sTxtError = '_sys_txt_access_denied';

        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();
                
        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        if(empty($mixedContent) || !is_array($mixedContent))
            return _t($sTxtError);

        if((int)$mixedContent[$CNF['FIELD_SHIPPED']] != 0 || $mixedContent[$CNF['FIELD_AUTHOR']] != $iProfileId)
            return _t($sTxtError);

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isAllowedMarkReceived($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedMarkReceived($mixedContent, $isPerformAction) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedMarkReceived($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedMarkReceivedForProfile($mixedContent, $isPerformAction);
    }

    public function checkAllowedMarkReceivedForProfile($mixedContent, $isPerformAction = false, $iProfileId = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $sTxtError = '_sys_txt_access_denied';

        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        if(empty($mixedContent) || !is_array($mixedContent))
            return _t($sTxtError);

        if((int)$mixedContent[$CNF['FIELD_RECEIVED']] != 0 || !$this->_oDb->hasLicense($iProfileId, $mixedContent[$CNF['FIELD_ID']]))
            return _t($sTxtError);

        return CHECK_ACTION_RESULT_ALLOWED;
    }
    
    public function onOfferAdded($iOfferId, &$aResult)
    {
        $CNF = &$this->_oConfig->CNF;

        $aOfferInfo = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iOfferId));
        if(empty($aOfferInfo) || !is_array($aOfferInfo))
            return;

        $iContentId = (int)$aOfferInfo[$CNF['FIELD_OFR_CONTENT']];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        $iViewer = bx_get_logged_profile_id();        
        $oViewer = BxDolProfile::getInstanceMagic($iViewer);

        sendMailTemplate($CNF['ETEMPLATE_OFFER_ADDED'], 0, $aContentInfo[$CNF['FIELD_AUTHOR']], array(
            'viewer_name' => $oViewer->getDisplayName(),
            'viewer_url' => $oViewer->getUrl(),
            'entry_name' => $aContentInfo[$CNF['FIELD_TITLE']],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $iContentId
            ))
        ));

        $aParams = $this->_alertParamsOffer($aContentInfo, $aOfferInfo);
        $aParams['override_result'] = &$aResult;
        bx_alert($this->getName(), 'offer_added', $iOfferId, $aOfferInfo[$CNF['FIELD_OFR_AUTHOR']], $aParams);
    }

    public function onOfferAccepted($iOfferId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aOfferInfo = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iOfferId));
        if(empty($aOfferInfo) || !is_array($aOfferInfo))
            return;

        $iContentId = (int)$aOfferInfo[$CNF['FIELD_OFR_CONTENT']];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        if(!$this->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => BX_ADS_STATUS_OFFER), array($CNF['FIELD_ID'] => $iContentId)))
            return;

        $iOfferer = (int)$aOfferInfo[$CNF['FIELD_OFR_AUTHOR']];
        $oOfferer = BxDolProfile::getInstanceMagic($iOfferer);

        sendMailTemplate($CNF['ETEMPLATE_OFFER_ACCEPTED'], 0, $iOfferer, array(
            'offerer_name' => $oOfferer->getDisplayName(),
            'offerer_url' => $oOfferer->getUrl(),
            'entry_name' => $aContentInfo[$CNF['FIELD_TITLE']],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $iContentId
            ))
        ));

        $aParams = $this->_alertParamsOffer($aContentInfo, $aOfferInfo);
        bx_alert($this->getName(), 'offer_accepted', $iOfferId, $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }

    public function onOfferDeclined($iOfferId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aOfferInfo = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iOfferId));
        if(empty($aOfferInfo) || !is_array($aOfferInfo))
            return;

        $iContentId = (int)$aOfferInfo[$CNF['FIELD_OFR_CONTENT']];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        $iOfferer = (int)$aOfferInfo[$CNF['FIELD_OFR_AUTHOR']];
        $oOfferer = BxDolProfile::getInstanceMagic($iOfferer);

        sendMailTemplate($CNF['ETEMPLATE_OFFER_DECLINED'], 0, $iOfferer, array(
            'offerer_name' => $oOfferer->getDisplayName(),
            'offerer_url' => $oOfferer->getUrl(),
            'entry_name' => $aContentInfo[$CNF['FIELD_TITLE']],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $iContentId
            ))
        ));

        $aParams = $this->_alertParamsOffer($aContentInfo, $aOfferInfo);
        bx_alert($this->getName(), 'offer_declined', $iOfferId, $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }

    public function onOfferCanceled($iOfferId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aOfferInfo = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iOfferId));
        if(empty($aOfferInfo) || !is_array($aOfferInfo))
            return;

        $iContentId = (int)$aOfferInfo[$CNF['FIELD_OFR_CONTENT']];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return;

        if(!$this->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => BX_BASE_MOD_TEXT_STATUS_ACTIVE), array($CNF['FIELD_ID'] => $iContentId)))
            return;

        $iOfferer = (int)$aOfferInfo[$CNF['FIELD_OFR_AUTHOR']];
        $oOfferer = BxDolProfile::getInstanceMagic($iOfferer);

        sendMailTemplate($CNF['ETEMPLATE_OFFER_CANCELED'], 0, (int)$aContentInfo[$CNF['FIELD_AUTHOR']], array(
            'offerer_name' => $oOfferer->getDisplayName(),
            'offerer_url' => $oOfferer->getUrl(),
            'entry_name' => $aContentInfo[$CNF['FIELD_TITLE']],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $iContentId
            ))
        ));

        $aParams = $this->_alertParamsOffer($aContentInfo, $aOfferInfo);
        bx_alert($this->getName(), 'offer_canceled', $iOfferId, $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }

    /**
     * Common methods.
     */
    public function onShipped($mixedContent, $iProfileSrc = 0, $iProfileDst = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);
        $iContentId = (int)$mixedContent[$CNF['FIELD_ID']];

        if(empty($iProfileSrc))
            $iProfileSrc = (int)$mixedContent[$CNF['FIELD_AUTHOR']];

        $oProfileSrc = BxDolProfile::getInstanceMagic($iProfileSrc);

        $aOffer = $this->_oDb->getOffersBy(array('type' => 'accepted', 'content_id' => $iContentId));
        $bOffer = !empty($aOffer) && is_array($aOffer);

        if(empty($iProfileDst) && $bOffer)
            $iProfileDst = (int)$aOffer[$CNF['FIELD_OFR_AUTHOR']];        

        if(!empty($oProfileSrc) && !empty($iProfileDst))
            sendMailTemplate($CNF['ETEMPLATE_SHIPPED'], 0, $iProfileDst, array(
                'vendor_name' => $oProfileSrc->getDisplayName(),
                'vendor_url' => $oProfileSrc->getUrl(),
                'entry_name' => $mixedContent[$CNF['FIELD_TITLE']],
                'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                    'i' => $CNF['URI_VIEW_ENTRY'], 
                    $CNF['FIELD_ID'] => $iContentId
                ))
            ));

        $aParams = $this->_alertParams($mixedContent);
        $aParams = array_merge($aParams, array(
            'profile_src' => $iProfileSrc,
            'profile_dst' => $iProfileDst,
            'offer_id' => $bOffer ? (int)$aOffer[$CNF['FIELD_OFR_ID']] : 0,
        ));

        bx_alert($this->getName(), 'shipped', $iContentId, false, $aParams);
    }

    public function onReceived($mixedContent, $iProfileSrc = 0, $iProfileDst = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);
        $iContentId = (int)$mixedContent[$CNF['FIELD_ID']];

        if(empty($iProfileDst))
            $iProfileDst = (int)$mixedContent[$CNF['FIELD_AUTHOR']];

        $aOffer = $this->_oDb->getOffersBy(array('type' => 'accepted', 'content_id' => $iContentId));
        $bOffer = !empty($aOffer) && is_array($aOffer);

        if(empty($iProfileSrc) && $bOffer)
            $iProfileSrc = (int)$aOffer[$CNF['FIELD_OFR_AUTHOR']];
        $oProfileSrc = BxDolProfile::getInstanceMagic($iProfileSrc);

        if(!empty($iProfileSrc) && !empty($iProfileDst))
            sendMailTemplate($CNF['ETEMPLATE_RECEIVED'], 0, $iProfileDst, array(
                'client_name' => $oProfileSrc->getDisplayName(),
                'client_url' => $oProfileSrc->getUrl(),
                'entry_name' => $mixedContent[$CNF['FIELD_TITLE']],
                'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                    'i' => $CNF['URI_VIEW_ENTRY'], 
                    $CNF['FIELD_ID'] => $iContentId
                ))
            ));

        $aParams = $this->_alertParams($mixedContent);
        $aParams = array_merge($aParams, array(
            'profile_src' => $iProfileSrc,
            'profile_dst' => $iProfileDst,
            'offer_id' => $bOffer ? (int)$aOffer[$CNF['FIELD_OFR_ID']] : 0,
        ));

        bx_alert($this->getName(), 'received', $iContentId, false, $aParams);
    }

    public function offerAccept($iId) 
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oDb->updateOffer(array($CNF['FIELD_OFR_STATUS'] => BX_ADS_OFFER_STATUS_ACCEPTED), array($CNF['FIELD_OFR_ID'] => $iId)))
            return false;
    
        $this->onOfferAccepted($iId);
        return true;        
    }

    public function offerDecline($iId) 
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oDb->updateOffer(array($CNF['FIELD_OFR_STATUS'] => BX_ADS_OFFER_STATUS_DECLINED), array($CNF['FIELD_OFR_ID'] => $iId)))
            return false;
    
        $this->onOfferDeclined($iId);
        return true;        
    }

    public function offerCancel($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_oDb->updateOffer(array($CNF['FIELD_OFR_STATUS'] => BX_ADS_OFFER_STATUS_CANCELED), array($CNF['FIELD_OFR_ID'] => $iId)))
            return false;

        $this->onOfferCanceled($iId);
        return true;
    }
    
    public function offerPay($iId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aOffer = $this->_oDb->getOffersBy(array('type' => 'id', 'id' => $iId));
        if(empty($aOffer) || !is_array($aOffer))
            return false;

        $iContent = (int)$aOffer[$CNF['FIELD_OFR_CONTENT']];
        $aContent = $this->_oDb->getContentInfoById($iContent);
        $iContentAuthor = (int)$aContent[$CNF['FIELD_AUTHOR']];

        $aReturn = array(
            'redirect' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php', array(
                'i' => $CNF['URI_VIEW_ENTRY'], 
                $CNF['FIELD_ID'] => $aOffer[$CNF['FIELD_OFR_CONTENT']]
            ))
        );

        $oPayments = BxDolPayments::getInstance();
        $aResult = $oPayments->addToCart($iContentAuthor, $this->getName(), $iContent, $aOffer[$CNF['FIELD_OFR_QUANTITY']]);
        if(!empty($aResult) && is_array($aResult)) {
            if(!empty($aResult['message']))
                $aReturn['msg'] = $aResult['message'];

            if(isset($aResult['code']) && (int)$aResult['code'] == 0)
                $aReturn['redirect'] = $oPayments->getCartUrl($iContentAuthor);
        }

        return $aReturn;
    }

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
    protected function _actionMarkAs($sAction)
    {
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(($mixedResult = $this->serviceMarkAs($sAction, $iContentId)) !== true)
            return array('msg' => $mixedResult); 

        return array(
            'reload' => 1
        );
    }

    protected function _actionChangeStatus($sStatus)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        if(($mixedResult = $this->checkAllowedEdit($aContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return array('msg' => $mixedResult);

        if(!$this->_oDb->updateEntriesBy(array($CNF['FIELD_STATUS'] => $sStatus), array($CNF['FIELD_ID'] => $iContentId)))
            return array('msg' => _t('_bx_ads_txt_err_cannot_perform_action'));

        return array(
            'reload' => 1
        );
    }

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
        $aCategories = $this->_oDb->getCategories(array('type' => 'parent_id', 'parent_id' => $iParentId, 'active' => true));
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

    protected function _getBlockLicenses($sType = '') 
    {
        $CNF = &$this->_oConfig->CNF;

        $sGrid = $CNF['OBJECT_GRID_LICENSES' . (!empty($sType) ? '_' . strtoupper($sType) : '')];
        $oGrid = BxDolGrid::getObjectInstance($sGrid);
        if(!$oGrid)
            return '';

        $this->_oDb->updateLicense(array('new' => 0), array('profile_id' => bx_get_logged_profile_id(), 'new' => 1));

        $this->_oTemplate->addJs(array('licenses.js'));
        return array(
            'content' => $this->_oTemplate->getJsCode('licenses', array('sObjNameGrid' => $sGrid)) . $oGrid->getCode(),
            'menu' => $CNF['OBJECT_MENU_LICENSES']
        );
    }

    protected function _alertParamsOffer($aContentInfo, $aOfferInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $aParams = array(
            'object_id' => (int)$aContentInfo[$CNF['FIELD_ID']],
            'object_author_id' => (int)$aContentInfo[$CNF['FIELD_AUTHOR']],

            'offer_id' => (int)$aOfferInfo[$CNF['FIELD_OFR_ID']],
            'offer_author_id' => (int)$aOfferInfo[$CNF['FIELD_OFR_AUTHOR']],
        );
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
            $aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        return $aParams;
    }
}

/** @} */
