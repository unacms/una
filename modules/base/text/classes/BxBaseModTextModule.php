<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_BASE_MOD_TEXT_STATUS_ACTIVE', 'active');
define('BX_BASE_MOD_TEXT_STATUS_HIDDEN', 'hidden');
define('BX_BASE_MOD_TEXT_STATUS_AWAITING', 'awaiting');
/**
 * Saved for backward compatibility.
 * Can be removed in future releases of UNA 13.
 */
define('BX_BASE_MOD_TEXT_STATUS_PENDING', 'pending');

/**
 * Base module class for text based modules
 */
class BxBaseModTextModule extends BxBaseModGeneralModule implements iBxDolContentInfoService
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }


    // ====== ACTIONS METHODS

    public function actionEmbedPoll($iPollId = 0)
    {
        if(empty($iPollId) && bx_get('poll_id') !== false)
            $iPollId = (int)bx_get('poll_id');

        $aParams = bx_get_with_prefix('param');
        array_walk($aParams, function(&$sValue) {
            $sValue = bx_process_input($sValue);
        });

        $this->_oTemplate->embedPollItem($iPollId, $aParams);
    }

    public function actionEmbedPolls($iId = 0)
    {
        list($iContentId, $aContentInfo) = $this->_getContent($iId);
        if($iContentId === false)
            return;

        $aParams = bx_get_with_prefix('param');
        array_walk($aParams, function(&$sValue) {
            $sValue = bx_process_input($sValue);
        });

        $this->_oTemplate->embedPollItems($aContentInfo, $aParams);
    }

    public function actionGetPoll()
    {
        $iPollId = (int)bx_get('poll_id');
        $sView = bx_process_input(bx_get('view'));

        $sMethod = 'serviceGetBlockPoll' . bx_gen_method_name($sView);
        if(!method_exists($this, $sMethod))
            return echoJson(array());

        $aBlock = $this->$sMethod($iPollId, true);
        if(empty($aBlock) || !is_array($aBlock))
            return echoJson(array());

        return echoJson(array(
            'content' => $aBlock['content']
        ));
    }
    public function actionDeletePoll()
    {
        $CNF = &$this->_oConfig->CNF;

        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iId))
            return echoJson(array());

        $aResult = array();
        if($this->_oDb->deletePolls(array($CNF['FIELD_POLL_ID'] => $iId)))
            $aResult = array('code' => 0);
        else
            $aResult = array('code' => 1, 'message' => _t($CNF['txt_err_cannot_perform_action']));

        echoJson($aResult);
    }

    public function actionGetPollForm()
    {
        echo $this->_oTemplate->getPollForm();
    }

    public function actionSubmitPollForm()
    {
        echoJson($this->getPollForm());
    }
    
    public function actionGetAttachLinkForm()
    {
        $iContentId = 0;
        if(bx_get('content_id') !== false)
            $iContentId = (int)bx_get('content_id');

        echo $this->_oTemplate->getAttachLinkForm($iContentId);
    }
    
    public function actionSubmitAttachLinkForm()
    {
        echoJson($this->getFormAttachLink());
    }
    
    public function actionAddAttachLink()
    {
        $sUrl = bx_process_input(bx_get('url'));
        if(empty($sUrl))
            return echoJson(array());
        
        $sUrl = htmlspecialchars_decode($sUrl);

        $sHeader = 'Content-Type';
        $aHeaders = @get_headers($sUrl, 1);
        if(!empty($aHeaders) && is_array($aHeaders) && !empty($aHeaders[$sHeader])) {
            $mixedContentType = $aHeaders[$sHeader];
            if(!is_array($mixedContentType))
                $mixedContentType = array($mixedContentType);

            foreach($mixedContentType as $sContentType)
                if(strpos($sContentType, 'image') !== false) 
                    return echoJson(array());
        }

        $iContentId = 0;
        if(bx_get('content_id') !== false)
            $iContentId = (int)bx_get('content_id');

        echoJson($this->addAttachLink(array(
            'content_id' => $iContentId,
            'url' => $sUrl
        )));
    }
    
    public function actionDeleteAttachLink()
    {
        $CNF = &$this->_oConfig->CNF;
        
    	$iUserId = $this->getUserId();
        $iLinkId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iLinkId))
            return echoJson(array());

        $aLink = $this->_oDb->getLinksBy(array('type' => 'id', 'id' => $iLinkId, 'profile_id' => $iUserId));
    	if(empty($aLink) || !is_array($aLink))
            return echoJson(array());

        if(!empty($aLink['media_id']))
            BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_PHOTOS'])->deleteFile($aLink['media_id']);

        $aResult = array();
        if($this->_oDb->deleteLink($iLinkId))
            $aResult = array('code' => 0);
        else
            $aResult = array('code' => 1, 'message' => _t('_bx_posts_form_post_input_link_err_delete'));

        echoJson($aResult);
    }

    public function actionFileEmbedVideo($iFileId)
    {
        $this->_oTemplate->embedVideo($iFileId);
    }

    public function actionFileEmbedSound($iFileId)
    {
        $this->_oTemplate->embedSound($iFileId);
    }


    // ====== SERVICE METHODS

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'GetBlockPollAnswers' => '',
            'GetBlockPollResults' => '',
            'GetMenuAddonManageToolsProfileStats' => '',
            'BrowsePublic' => '',
            'BrowsePopular' => '',
            'BrowseTop' => '',
            'BrowseUpdated' => '',
            'BrowseAuthor' => '',
            'CategoriesMultiListContext' => ''
        ));
    }

    public function serviceManageTools($sType = 'common')
    {
        $sResult = parent::serviceManageTools($sType);
        if(!empty($sResult))
            $this->_oTemplate->addJsSystem(['modules/base/text/js/|manage_tools.js']);

        return $sResult;
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-categories_multi_list_context categories_multi_list_context
     * 
     * @code bx_srv('bx_posts', 'categories_multi_list_context', [...]); @endcode
     * 
     * Display multi-categorories block with number of posts in each category for current context
     * @param $bDisplayEmptyCats display empty categories
     * 
     * @see BxBaseModGeneralModule::serviceCategoriesMultiListContext
     */
    /** 
     * @ref bx_base_general-categories_multi_list_context "categories_multi_list_context"
     */
    public function serviceCategoriesMultiListContext($iProfileId = 0, $bDisplayEmptyCats = true)
    {
        if ($iProfileId == 0)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        
		$oCategories = BxDolCategories::getInstance();
        $aCats = $oCategories->getData([
                'type' => 'by_module&context_with_num', 
                'module' => $this->getName(), 
                'context_id' => $iProfileId
            ]
        );
        $aVars = array('bx_repeat:cats' => array());
        foreach ($aCats as $oCat) {
            $sValue = $oCat['value'];
            $iNum = $oCat['num'];
            
            $aVars['bx_repeat:cats'][] = array(
                'url' => $oCategories->getUrl($this->getName(), $sValue, '&context_id=' . $iProfileId),
                'name' => _t($sValue),
                'value' => $sValue,
                'num' => $iNum,
            );
        }
        
        if (!$aVars['bx_repeat:cats'])
            return '';

        return $this->_oTemplate->parseHtmlByName('category_list_multi.html', $aVars);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-polls Polls Blocks
     * @subsubsection bx_base_text-get_block_poll_answers get_block_poll_answers
     * 
     * @code bx_srv('bx_posts', 'get_block_poll_answers', [...]); @endcode
     * 
     * Get block with poll answers
     * @param $iPollId poll ID
     * 
     * @see BxBaseModTextModule::serviceGetBlockPollAnswers
     */
    /** 
     * @ref bx_base_text-get_block_poll_answers "get_block_poll_answers"
     */
    public function serviceGetBlockPollAnswers($iPollId, $bForceDisplay = false)
    {
        if(!$iPollId)
            return false;

        if(!$bForceDisplay && $this->isPollPerformed($iPollId))
            return $this->serviceGetBlockPollResults($iPollId);

        return $this->_serviceTemplateFunc('entryPollAnswers', $iPollId, 'getPollInfoById');
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-polls Polls Blocks
     * @subsubsection bx_base_text-get_block_poll_results get_block_poll_results
     * 
     * @code bx_srv('bx_posts', 'get_block_poll_results', [...]); @endcode
     * 
     * Get block with poll results
     * @param $iPollId poll ID
     * 
     * @see BxBaseModTextModule::serviceGetBlockPollResults
     */
    /** 
     * @ref bx_base_text-get_block_poll_results "get_block_poll_results"
     */
    public function serviceGetBlockPollResults($iPollId)
    {
        return $this->_serviceTemplateFunc('entryPollResults', $iPollId, 'getPollInfoById');
    }
    
    /**
     * Display media EXIF information.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with EXIF info. On error empty string is returned.
     */ 
    public function serviceMediaExif ($iMediaId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        return $this->_serviceTemplateFunc ('mediaExif', $iMediaId, $CNF['FUNCTION_FOR_GET_ITEM_INFO']);
    }

    public function serviceGetThumb ($iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;
        if(bx_is_api()){
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
            return bx_api_get_image($CNF['OBJECT_STORAGE'], $aContentInfo[$CNF['FIELD_THUMB']]);
        }
            
        if(empty($sTranscoder) && !empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            $sTranscoder = $CNF['OBJECT_IMAGES_TRANSCODER_GALLERY'];

        $mixedResult = $this->_getFieldValueThumb('FIELD_THUMB', $iContentId, $sTranscoder);
        return $mixedResult !== false ? $mixedResult : '';
    }

    public function serviceGetMenuAddonManageTools()
    {
        $CNF = &$this->_oConfig->CNF;
        
        $iNumTotal = $this->_oDb->getEntriesNumByParams();

        $iNum1 = $this->_oDb->getEntriesNumByParams([
            [
                'key' => $CNF['FIELD_STATUS'], 
                'value' => 'hidden', 
                'operator' => '='
            ]
        ]);
        
        $iNum2 = 0;
        if (isset($CNF['OBJECT_REPORTS'])){
            $iNum2 = $this->_oDb->getEntriesNumByParams([
                [
                    'key' => 'reports',
                    'value' => '0', 
                    'operator' => '>'
                ]
            ]);
        }
        return array('counter1_value' => $iNum1, 'counter2_value' => $iNum2, 'counter3_value' => $iNumTotal );
	}

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-other Other
     * @subsubsection bx_base_text-get_menu_addon_manage_tools_profile_stats get_menu_addon_manage_tools_profile_stats
     * 
     * @code bx_srv('bx_posts', 'get_menu_addon_manage_tools_profile_stats', []); @endcode
     * 
     * Get number of posts for currently logged in user
     * 
     * @see BxBaseModTextModule::serviceGetMenuAddonManageToolsProfileStats
     */
    /** 
     * @ref bx_base_text-get_menu_addon_manage_tools_profile_stats "get_menu_addon_manage_tools_profile_stats"
     */
	public function serviceGetMenuAddonManageToolsProfileStats()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'author' => bx_get_logged_profile_id()
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-browse Browse
     * @subsubsection bx_base_text-browse_public browse_public
     * 
     * @code bx_srv('bx_posts', 'browse_public', [...]); @endcode
     * 
     * Display public posts
     * @param $sUnitView unit view, such as: full, extended, gallery, showcase
     * @param $bEmptyMessage display or not "empty" message when there is no content
     * @param $bAjaxPaginate use AJAX paginate or not
     * 
     * @see BxBaseModTextModule::serviceBrowsePublic
     */
    /** 
     * @ref bx_base_text-browse_public "browse_public"
     */
    public function serviceBrowsePublic ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {   
        return $this->_serviceBrowse ('public', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-browse Browse
     * @subsubsection bx_base_text-browse_popular browse_popular
     * 
     * @code bx_srv('bx_posts', 'browse_popular', [...]); @endcode
     * 
     * Display popular posts
     * @param $sUnitView unit view, such as: full, extended, gallery, showcase
     * @param $bEmptyMessage display or not "empty" message when there is no content
     * @param $bAjaxPaginate use AJAX paginate or not
     * 
     * @see BxBaseModTextModule::serviceBrowsePopular
     */
    /** 
     * @ref bx_base_text-browse_popular "browse_popular"
     */
    public function serviceBrowsePopular ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-browse Browse
     * @subsubsection bx_base_text-browse_top browse_top
     * 
     * @code bx_srv('bx_posts', 'browse_top', [...]); @endcode
     * 
     * Display top posts
     * @param $sUnitView unit view, such as: full, extended, gallery, showcase
     * @param $bEmptyMessage display or not "empty" message when there is no content
     * @param $bAjaxPaginate use AJAX paginate or not
     * 
     * @see BxBaseModTextModule::serviceBrowseTop
     */
    /** 
     * @ref bx_base_text-browse_top "browse_top"
     */
    public function serviceBrowseTop ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('top', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-browse Browse
     * @subsubsection bx_base_text-browse_updated browse_updated
     * 
     * @code bx_srv('bx_posts', 'browse_updated', [...]); @endcode
     * 
     * Display recently updated posts
     * @param $sUnitView unit view, such as: full, extended, gallery, showcase
     * @param $bEmptyMessage display or not "empty" message when there is no content
     * @param $bAjaxPaginate use AJAX paginate or not
     * 
     * @see BxBaseModTextModule::serviceBrowseUpdated
     */
    /** 
     * @ref bx_base_text-browse_updated "browse_updated"
     */
    public function serviceBrowseUpdated ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('updated', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-browse Browse
     * @subsubsection bx_base_text-browse_author browse_author
     * 
     * @code bx_srv('bx_posts', 'browse_author', [...]); @endcode
     * 
     * Display posts of specified author
     * @param $iProfileId profile ID
     * @param $aParams additional params, see BxBaseModGeneralModule::serviceBrowse
     * 
     * @see BxBaseModTextModule::serviceBrowseAuthor
     */
    /** 
     * @ref bx_base_text-browse_author "browse_author"
     */
    public function serviceBrowseAuthor ($iProfileId = 0, $aParams = array())
    {
        return $this->_serviceBrowseWithParam ('author', 'profile_id', $iProfileId, $aParams);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-page_blocks Page Blocks
     * @subsubsection bx_base_text-entity_author entity_author
     * 
     * @code bx_srv('bx_posts', 'entity_author', [...]); @endcode
     * 
     * Display author block for the specified post
     * @param $iContentId content ID
     * 
     * @see BxBaseModTextModule::serviceEntityAuthor
     */
    /** 
     * @ref bx_base_text-entity_author "entity_author"
     */
    public function serviceEntityAuthor ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAuthor', $iContentId);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-page_blocks Page Blocks
     * @subsubsection bx_base_text-entity_polls entity_polls
     * 
     * @code bx_srv('bx_posts', 'entity_polls', [...]); @endcode
     * 
     * Display polls for the specified post
     * @param $iContentId content ID
     * 
     * @see BxBaseModTextModule::serviceEntityPolls
     */
    /** 
     * @ref bx_base_text-entity_polls "entity_polls"
     */
    public function serviceEntityPolls ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryPolls', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_text Base Text
     * @subsection bx_base_text-page_blocks Page Blocks
     * @subsubsection bx_base_text-entity_breadcrumb entity_breadcrumb
     * 
     * @code bx_srv('bx_forum', 'entity_breadcrumb', [...]); @endcode
     * 
     * Display breadcrumb for the specified post
     * @param $iContentId content ID
     * 
     * @see BxBaseModTextModule::serviceEntityBreadcrumb
     */
    /** 
     * @ref bx_base_text-entity_breadcrumb "entity_breadcrumb"
     */
    public function serviceEntityBreadcrumb ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryBreadcrumb', $iContentId);
    }

    /**
     * Delete all content by profile 
     * @param $iProfileId profile id 
     * @return number of deleted items
     */
    public function serviceDeleteEntitiesByAuthor ($iProfileId)
    {
        $a = $this->_oDb->getEntriesByAuthor((int)$iProfileId);
        if (!$a)
            return 0;

        $iCount = 0;
        foreach ($a as $aContentInfo)
            $iCount += ('' == $this->serviceDeleteEntity($aContentInfo[$this->_oConfig->CNF['FIELD_ID']]) ? 1 : 0);

        return $iCount;
    }


    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb ($iContentId = 0)
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set thumb', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isAllowedApprove($mixedContent, $isPerformAction = false)
    {
        return $this->checkAllowedApprove($mixedContent, $isPerformAction) === CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedApprove($mixedContent, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $sTxtError = '_sys_txt_access_denied';
        $iProfileId = bx_get_logged_profile_id();

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        if(empty($mixedContent) || !is_array($mixedContent))
            return _t($sTxtError);
        
        if(!isset($CNF['FIELD_STATUS_ADMIN']) || $mixedContent[$CNF['FIELD_STATUS_ADMIN']] != BX_BASE_MOD_GENERAL_STATUS_PENDING)
            return _t($sTxtError);

        if($this->_isModerator())
            return CHECK_ACTION_RESULT_ALLOWED;

        if(!empty($CNF['FIELD_ALLOW_VIEW_TO']) && (int)$mixedContent[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $iContextProfileId = abs((int)$mixedContent[$CNF['FIELD_ALLOW_VIEW_TO']]);
            $oContextProfile = BxDolProfile::getInstance($iContextProfileId);

            $aAdmins = bx_srv($oContextProfile->getModule(), 'get_admins_to_manage_content', [$iContextProfileId]);
            if(in_array($iProfileId, $aAdmins))
                return CHECK_ACTION_RESULT_ALLOWED;
        }
        
        return _t($sTxtError);
    }    

    public function isPollPerformed($iObjectId, $iAuthorId = 0, $iAuthorIp = 0)
    {
        if(empty($iAuthorId)) {
            $iAuthorId = bx_get_logged_profile_id();
            $iAuthorIp = bx_get_ip_hash(getVisitorIP());
        }

        return $this->_oDb->isPollPerformed($iObjectId, $iAuthorId, $iAuthorIp);
    }


    // ====== COMMON METHODS
    public function addAttachLink($aValues, $sDisplay = false)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$sDisplay)
            $sDisplay = $CNF['OBJECT_FORM_DISPLAY_ATTACH_LINK_ADD'];

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ATTACH_LINK'], $sDisplay, $this->_oTemplate);
        if(!$oForm)
            return array('message' => '_sys_txt_error_occured');

        $oForm->aFormAttrs['method'] = BX_DOL_FORM_METHOD_SPECIFIC;
        $oForm->aParams['csrf']['disable'] = true;
        if(!empty($oForm->aParams['db']['submit_name'])) {
            $sSubmitName = $oForm->aParams['db']['submit_name'];
            if(!isset($oForm->aInputs[$sSubmitName])) {
                if(isset($oForm->aInputs[$CNF['FIELD_ATTACH_LINK_CONTROLS']]))
                    foreach($oForm->aInputs[$CNF['FIELD_ATTACH_LINK_CONTROLS']] as $mixedIndex => $aInput) {
                        if(!is_numeric($mixedIndex) || empty($aInput['name']) || $aInput['name'] != $sSubmitName)
                            continue;
    
                        $aValues[$sSubmitName] = $aInput['value'];
                    }
            }
            else            
                $aValues[$sSubmitName] = $oForm->aInputs[$sSubmitName]['value'];
        }

        $oForm->aInputs['url']['checker']['params']['preg'] = $this->_oConfig->getPregPattern('url');

        $oForm->initChecker(array(), $aValues);
        if(!$oForm->isSubmittedAndValid())
            return array('message' => '_sys_txt_error_occured');

        return $this->_addLink($oForm);
    }
    
    public function getFormAttachLink($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ATTACH_LINK'], $CNF['OBJECT_FORM_DISPLAY_ATTACH_LINK_ADD'], $this->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_attach_link_form/';
        $oForm->aInputs['content_id']['value'] = $iContentId;
        $oForm->aInputs['url']['checker']['params']['preg'] = $this->_oConfig->getPregPattern('url');

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid())
            return $this->_addLink($oForm);

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }
    
    protected function _addLink(&$oForm)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $iUserId = $this->getUserId();

        $iContentId = (int)$oForm->getCleanValue('content_id');
        $sLink = rtrim($oForm->getCleanValue('url'), '/');
        $sHost = parse_url($sLink, PHP_URL_HOST);
        if (is_private_ip(gethostbyname($sHost)))
            return array('message' => _t('_sys_txt_error_occured'));

        $aMatches = array();
        preg_match($this->_oConfig->getPregPattern('url'), $sLink, $aMatches);
        $sLink = (empty($aMatches[2]) ? 'http://' : '') . $aMatches[0];

        $aSiteInfo = bx_get_site_info($sLink, array(
            'thumbnailUrl' => array('tag' => 'link', 'content_attr' => 'href'),
            'OGImage' => array('name_attr' => 'property', 'name' => 'og:image'),
        ));

        $sTitle = !empty($aSiteInfo['title']) ? $aSiteInfo['title'] : _t('_Empty');
        $sDescription = !empty($aSiteInfo['description']) ? $aSiteInfo['description'] : _t('_Empty');

        $sMediaUrl = '';
        if(!empty($aSiteInfo['thumbnailUrl']))
            $sMediaUrl = $aSiteInfo['thumbnailUrl'];
        else if(!empty($aSiteInfo['OGImage']))
            $sMediaUrl = $aSiteInfo['OGImage'];

        $iMediaId = 0;
        $oStorage = null;
        if(!empty($sMediaUrl)) {
            $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_PHOTOS']);

            $iMediaId = $oStorage->storeFileFromUrl($sMediaUrl, true, $iUserId);
        }

        $iId = (int)$oForm->insert(array('profile_id' => $iUserId, 'media_id' => $iMediaId, 'url' => $sLink, 'title' => $sTitle, 'text' => $sDescription, 'added' => time()));
        if(!empty($iId)) {
            if(!empty($oStorage) && !empty($iMediaId))
                $oStorage->afterUploadCleanup($iMediaId, $iUserId);

            return array(
                'id' => $iId, 
                'content_id' => $iContentId, 
                'url' => $sLink,
                'item' => $this->_oTemplate->getAttachLinkItem($iUserId, $iId)
            );
        }

        return array('message' => _t('_bx_timeline_txt_err_cannot_perform_action'));
    }

    public function onApprove($mixedContent)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        $this->alertAfterApprove($mixedContent);

        $this->onPublished($mixedContent[$CNF['FIELD_ID']]);
    }

    public function alertAfterAdd($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $iId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $iAuthorId = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];

        $sAction = 'added';
        if(isset($CNF['FIELD_STATUS']) && isset($aContentInfo[$CNF['FIELD_STATUS']]) && $aContentInfo[$CNF['FIELD_STATUS']] == BX_BASE_MOD_TEXT_STATUS_AWAITING)
            $sAction = 'deferred';
        else if(isset($CNF['FIELD_STATUS_ADMIN']) && isset($aContentInfo[$CNF['FIELD_STATUS_ADMIN']]) && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] == BX_BASE_MOD_GENERAL_STATUS_PENDING)
            $sAction = 'deferred';

        $aParams = $this->_alertParamsAdd($aContentInfo);
        bx_alert('system', 'prepare_alert_params', 0, 0, array('unit'=> $this->getName(), 'action' => &$sAction, 'object_id' => &$iId, 'sender_id' => &$iAuthorId, 'extras' => &$aParams));
        bx_alert($this->getName(), $sAction, $iId, $iAuthorId, $aParams);

        $this->_processModerationNotifications($aContentInfo);
    }

    public function alertAfterEdit($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $iId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $aParams = $this->_alertParams($aContentInfo);
        bx_alert($this->getName(), 'edited', $iId, false, $aParams);
    }

    /**
     * Get array of params to be passed in Add/Edit Alert.
     */
    protected function _alertParams($aContentInfo)
    {
        $aParams = parent::_alertParams($aContentInfo);

        $CNF = &$this->_oConfig->CNF;

        if(!empty($CNF['FIELD_ALLOW_VIEW_TO']) && isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
            $aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        if(!empty($CNF['FIELD_CF']) && isset($aContentInfo[$CNF['FIELD_CF']]))
            $aParams['cf'] = $aContentInfo[$CNF['FIELD_CF']];

        return $aParams;
    }

    protected function _alertParamsAdd($aContentInfo)
    {
        $aParams = $this->_alertParams($aContentInfo);

        $CNF = &$this->_oConfig->CNF;

        if(!empty($CNF['OBJECT_METATAGS']))
            $aParams['timeline_group'] = array(
                'by' => $this->getName() . '_' . (int)$aContentInfo[$CNF['FIELD_AUTHOR']] . '_' . (int)$aContentInfo[$CNF['FIELD_ID']],
                'field' => 'owner_id'
            );

        return $aParams;
    }

    public function getPollForm()
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_FORM_POLL']))
            return array('code' => 1, 'message' => '_sys_txt_error_occured');

        $iProfileId = bx_get_logged_profile_id();

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_POLL'], $CNF['OBJECT_FORM_POLL_DISPLAY_ADD'], $this->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_poll_form/';

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iId = $oForm->insert();
            if($iId)
                return array('code' => 0, 'id' => $iId, 'item' => $this->_oTemplate->getPollItem($iId, $iProfileId, array(
                    'manage' => true
                )));
            else
                return array('code' => 2, 'message' => '_sys_txt_error_entry_creation');
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }
    
    public function getEntryImageData($aContentInfo, $sField = 'FIELD_THUMB', $aTranscoders = array())
    {
        $CNF = &$this->_oConfig->CNF;
        
        $mResult = parent::getEntryImageData($aContentInfo, $sField, $aTranscoders);
        if ($mResult === false &&  isset($CNF['PARAM_USE_GALERY_AS_COVER']) && getParam($CNF['PARAM_USE_GALERY_AS_COVER']) == 'on'){
            if(!empty($CNF['OBJECT_STORAGE_PHOTOS']) && !empty($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS'])){
				$sStorage = $CNF['OBJECT_STORAGE_PHOTOS'];
				$oStorage = BxDolStorage::getObjectInstance($sStorage); 
				$aGhostFiles = $oStorage->getGhosts ($this->serviceGetContentOwnerProfileId($aContentInfo[$CNF['FIELD_ID']]), $aContentInfo[$CNF['FIELD_ID']]);
				if ($aGhostFiles){
					foreach ($aGhostFiles as $k => $a) {
                        return array('id' => $a['id'], 'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS']);
					}
				}
                
			}
        }
        
        return $mResult;
    }


    // ====== PROTECTED METHODS

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
        $aResults = parent::_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        
        if (bx_is_api())
            return $aResults;
        
        if (count($aResults) == 0 && isset($CNF['PARAM_USE_GALERY_AS_COVER']) && getParam($CNF['PARAM_USE_GALERY_AS_COVER']) == 'on'){
            $aResults = $this->_getImagesForTimelinePostAttachInner($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
            if (count($aResults) > 1){
                $aResults = array_slice($aResults, 0, 1);
            }
        }
        return $aResults;
    }

    protected function _getImagesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
        $aTmp = parent::_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        $aResults = $this->_getImagesForTimelinePostAttachInner($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        
        if (bx_is_api())
            return $aResults;
        
        if (count($aTmp) == 0 && count($aResults) > 0 && isset($CNF['PARAM_USE_GALERY_AS_COVER']) && getParam($CNF['PARAM_USE_GALERY_AS_COVER']) == 'on'){
            $aResults = array_slice($aResults, 1);
        }
        return $aResults;
    }
    
    protected function _getImagesForTimelinePostAttachInner($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResults = parent::_getImagesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        
        if(!$this->_oConfig->isAttachmentsInTimeline() || empty($CNF['OBJECT_STORAGE_PHOTOS']) || empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS']))
            return $aResults;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_PHOTOS']);

        $aGhostFiles = $oStorage->getGhosts($this->serviceGetContentOwnerProfileId($iContentId), $iContentId);
        if(!$aGhostFiles)
            return [];

        $oTranscoderSm = false;
        if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_MINIATURE_PHOTOS']))
            $oTranscoderSm = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_MINIATURE_PHOTOS']);

        $oTranscoderMd = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS']);

        $oTranscoderXl = false;
        if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_VIEW_PHOTOS']))
            $oTranscoderXl = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_VIEW_PHOTOS']);

        foreach($aGhostFiles as $k => $a) {
            $sPhotoSrcMd = $oTranscoderMd->getFileUrl($a['id']);
            if(empty($sPhotoSrcMd))
                continue;

            $sPhotoSrcSm = $oTranscoderSm !== false ? $oTranscoderSm->getFileUrl($a['id']) : '';
            if(!$sPhotoSrcSm)
                $sPhotoSrcSm = $sPhotoSrcMd;

            $sPhotoSrcXl = $oTranscoderXl !== false ? $oTranscoderXl->getFileUrl($a['id']) : $oStorage->getFileUrlById($a['id']);
            if(!$sPhotoSrcXl)
                $sPhotoSrcXl = $sPhotoSrcMd;

            if (bx_is_api()){
                $aResults[] = bx_api_get_image($CNF['OBJECT_STORAGE_PHOTOS'], (int)$a['id']);
            }
            else{
                $aResults[] = [
                    'id' => $a['id'],
                    'src' => $sPhotoSrcMd,
                    'src_small' => $sPhotoSrcSm,
                    'src_medium' => $sPhotoSrcMd,
                    'src_orig' => $sPhotoSrcXl,
                ];
            }
        }
        return $aResults;
    }

    protected function _getVideosForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResults = parent::_getVideosForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams); 
        if(!$this->_oConfig->isAttachmentsInTimeline() || empty($CNF['OBJECT_STORAGE_VIDEOS']) || empty($CNF['OBJECT_VIDEOS_TRANSCODERS']))
            return $aResults;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_VIDEOS']);

        $aGhostFiles = $oStorage->getGhosts($this->serviceGetContentOwnerProfileId($iContentId), $iContentId);
        if(!$aGhostFiles)
            return array();

        $oTcPoster = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);
        $oTcMp4 = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $oTcMp4Hd = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd']);
        if(!$oTcPoster || !$oTcMp4 || !$oTcMp4Hd)
            return array();

        $aResults = array();
        foreach ($aGhostFiles as $k => $a) {
            $sVideoUrl = $oStorage->getFileUrlById($a['id']);
            $aVideoFile = $oStorage->getFile($a['id']);

            $sVideoUrlHd = '';
            if (!empty($aVideoFile['dimensions']) && $oTcMp4Hd->isProcessHD($aVideoFile['dimensions']))
                $sVideoUrlHd = $oTcMp4Hd->getFileUrl($a['id']);

            $aResults[$a['id']] = array(
                'id' => $a['id'],
                'src_poster' => $oTcPoster->getFileUrl($a['id']),
                'src_mp4' => $oTcMp4->getFileUrl($a['id']),
                'src_mp4_hd' => $sVideoUrlHd,
            );
        }

        return $aResults;
    }

    protected function _getFilesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResults = parent::_getFilesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        if(!$this->_oConfig->isAttachmentsInTimeline() || empty($CNF['OBJECT_STORAGE_FILES']))
            return $aResults;

        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE_FILES']);

        $aGhostFiles = $oStorage->getGhosts($this->serviceGetContentOwnerProfileId($iContentId), $iContentId);
        if(!$aGhostFiles)
            return array();

        $oTranscoder = null;
        if(!empty($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_FILES']))
            $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY_FILES']);

        foreach ($aGhostFiles as $k => $a) {
            $bImage = in_array($a['ext'], array('jpg', 'jpeg', 'png', 'gif'));

            $sPhotoSrc = '';
            if($bImage && $oTranscoder)
                $sPhotoSrc = $oTranscoder->getFileUrl($a['id']);

            if(empty($sPhotoSrc) && $oStorage)
                $sPhotoSrc = $this->_oTemplate->getIconUrl($oStorage->getIconNameByFileName($a['file_name']));

            $sUrl = $oStorage->getFileUrlById($a['id']);

            $aResults[] = array(
                'id' => $a['id'],
                'src' => $sPhotoSrc,
                'src_medium' => $sPhotoSrc,
                'src_orig' => $bImage ? $sUrl : '',
                'url' => !$bImage ? $sUrl : ''
            );
        }

        return $aResults;
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);
        if(!$this->_oConfig->isAttachmentsInTimeline())
            return $aResult;

        $CNF = &$this->_oConfig->CNF;

        $bDynamic = isset($aBrowseParams['dynamic_mode']) && (bool)$aBrowseParams['dynamic_mode'] === true;
		if(!empty($CNF['PARAM_POLL_ENABLED']) && $CNF['PARAM_POLL_ENABLED'] == true)
        	$aPolls = $this->_oDb->getPolls(array('type' => 'content_id', 'content_id' => (int)$aContentInfo[$CNF['FIELD_ID']]));
        if(!empty($aPolls) && is_array($aPolls)) {
            $sInclude = $this->_oTemplate->addCss(array('polls.css'), $bDynamic);

            $aResult['raw'] = ($bDynamic ? $sInclude : '') . $this->_oTemplate->parseHtmlByName('poll_items_embed.html', array(
                'embed_url' => BX_DOL_URL_ROOT . bx_append_url_params($this->_oConfig->getBaseUri() . 'embed_polls/', array(
                    'id' => (int)$aContentInfo[$CNF['FIELD_ID']],
                    'param_switch_menu' => 0,
                    'param_showcase' => 1
                ))
            ));
/*
            if((!empty($aResult['videos']) && is_array($aResult['videos'])) || (!empty($aResult['videos_attach']) && is_array($aResult['videos_attach'])))
                $aResult['_cache'] = false;
 */
        }
        return $aResult;
    }

    protected function _buildRssParams($sMode, $aArgs)
    {
        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'author':
                $aParams = array('author' => isset($aArgs[0]) ? (int)$aArgs[0] : '');
                break;
        }

        return $aParams;
    }

    public function serviceIsAllowedPostInContext()
    {
        return true;
    }
}

/** @} */
