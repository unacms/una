<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_BASE_MOD_GENERAL_STATUS_ACTIVE', 'active');
define('BX_BASE_MOD_GENERAL_STATUS_HIDDEN', 'hidden');
define('BX_BASE_MOD_GENERAL_STATUS_PENDING', 'pending');

bx_import('BxDolAcl');

/**
 * Base module class.
 */
class BxBaseModGeneralModule extends BxDolModule
{
    protected $_iProfileId;
    protected $_aSearchableNamesExcept;
    protected $_aFormParams;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_iProfileId = bx_get_logged_profile_id();
        $this->_aSearchableNamesExcept = array(
            'allow_view_to'
        );

        $this->_aFormParams = array(
            'display' => false, 
            'dynamic_mode' => false, 
            'ajax_mode' => false, 
            'absolute_action_url' => false,
            'visibility_autoselect' => false,
            'context_id' => 0,
            'custom' => array()
        );
    }

    // ====== ACTIONS METHODS

    public function isEntryActive($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $iProfileId = bx_get_logged_profile_id();
        if($aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId || $this->_isModerator())
            return true;

        if(!empty($CNF['FIELD_ALLOW_VIEW_TO']) && (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $iContextProfileId = abs((int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);
            $oContextProfile = BxDolProfile::getInstance($iContextProfileId);

            $aAdmins = bx_srv($oContextProfile->getModule(), 'get_admins_to_manage_content', [$iContextProfileId]);
            if(in_array($iProfileId, $aAdmins))
                return true;
        }

        if(isset($CNF['FIELD_STATUS']) && $aContentInfo[$CNF['FIELD_STATUS']] != 'active')
            return false;

        if(isset($CNF['FIELD_STATUS_ADMIN']) && isset($aContentInfo[$CNF['FIELD_STATUS_ADMIN']]) && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != 'active')
            return false;

        return true;        
    }

    
    public function actionUpdateImage($sFiledName, $iContentId, $sValue)
    {
        $CNF = &$this->_oConfig->CNF;
        $aData = $this->_oDb->getContentInfoById($iContentId);
        if ($this->checkAllowedEdit($aData) === CHECK_ACTION_RESULT_ALLOWED){
        
            $this->_oDb->updateEntriesBy([$sFiledName => $sValue], [$CNF['FIELD_ID'] => $iContentId]);
            //TODO add transcoder settings
            $oImgStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
            $sImageUrl = $oImgStorage->getFileUrlById($sValue);
            
            $this->actionUpdateImagePosition($iContentId, $sFiledName, '', '');
            
            echo $sImageUrl;
        }
        
    }
    
    public function actionUpdateImagePosition($iContentId, $sFiledName, $sH, $sV)
    {
        $CNF = &$this->_oConfig->CNF;
        $sFieldNamePos = 'FIELD_' . strtoupper($sFiledName) . '_POSITION';
        if (isset($CNF[$sFieldNamePos])){
            $sValue = '';
            $CNF = &$this->_oConfig->CNF;
            if ($sH != '' || $sV != '')
                $sValue = json_encode(['x' => $sH, 'y' => $sV]);
            $this->_oDb->updateEntriesBy([$CNF[$sFieldNamePos] => $sValue], [$CNF['FIELD_ID'] => $iContentId]);
        }
    }
    
    public function actionApprove()
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($CNF['FIELD_STATUS_ADMIN']))
            return echoJson([]);

        $iContentId = (int)bx_get('content_id');
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo) || $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != BX_BASE_MOD_GENERAL_STATUS_PENDING)
            return echoJson([]);

        if(($mixedCheckResult = $this->checkAllowedApprove($aContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $mixedCheckResult]);

        $oForm = $this->_getApproveForm($iContentId, $aContentInfo);
        $oForm->initChecker();

        if(!$oForm->isSubmitted() || !$oForm->isValid()) {
            $sPopupId = $this->getName() . '_approve_' . $iContentId;
            $sPopupTitle = '_sys_manage_popup_approve_title';
            if(!empty($CNF['T']['txt_approve_popup_title'])) 
                $sPopupTitle = $CNF['T']['txt_approve_popup_title'];

            return echoJson(['popup' => BxTemplFunctions::getInstance()->popupBox($sPopupId, _t($sPopupTitle), $oForm->getCode(true))]);
        }

        if($oForm->getCleanValue('do_send')) {
            $aResult = [];
            if($this->_sendApproveMessage($iContentId, $aContentInfo, $oForm))
                $aResult = ['msg' => _t('_sys_manage_txt_sent')];
            else
                $aResult = ['msg' => _t('_sys_manage_err_cannot_perform_action')];

            return echoJson($aResult);
        }

        if($oForm->getCleanValue('do_submit')) {
            $aResult = [];
            if($this->_oDb->updateEntriesBy([$CNF['FIELD_STATUS_ADMIN'] => BX_BASE_MOD_GENERAL_STATUS_ACTIVE], [$CNF['FIELD_ID'] => $iContentId])) {
                $this->checkAllowedApprove($aContentInfo, true);

                $this->onApprove($aContentInfo);

                /*
                 * Process metas.
                 * Note. It's essential to process metas a the very end, 
                 * because all data related to an entry should be already
                 * processed and are ready to be passed to alert. 
                 */
                $this->processMetasAdd($iContentId);

                $aResult = ['msg' => _t('_sys_manage_txt_approved'), 'reload' => 1];
            }
            else
                $aResult = ['msg' => _t('_sys_manage_err_cannot_perform_action')];

            return echoJson($aResult);
        }
    }

    public function actionRss ()
    {
        $aArgs = func_get_args();
        $this->_rss($aArgs);
    }

    public function actionGetCreatePostForm()
    {
        $sName = $this->_oConfig->getName();

    	$aParams = bx_process_input(array_intersect_key($_GET, $this->_aFormParams));
    	$aParams = array_merge($this->_aFormParams, $aParams);
        $aParams['context_id'] = (bool)$aParams['context_id'] ? (int)$aParams['context_id'] : false;

    	$mixedResponse = $this->serviceGetCreatePostForm($aParams);
    	if(empty($mixedResponse))
            return echoJson(array());
        else if(is_array($mixedResponse)) {
            $mixedResponse['module'] = $sName;
            return echoJson($mixedResponse);
        }

        return echoJson(array(
            'module' => $sName,
            'content' => $mixedResponse
    	));
    }

    public function actionGetNotes()
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_NOTES']))
            return echoJson([]);

        $iContentId = (int)bx_get('content_id');

        $oCmtsNotes = BxDolCmts::getObjectInstance($CNF['OBJECT_NOTES'], $iContentId, true, $this->_oTemplate);
        $aCmtsNotes = $oCmtsNotes->getCommentsBlock([], ['in_designbox' => false]);
        if(empty($aCmtsNotes) || !is_array($aCmtsNotes))
            return echoJson([]);

        $sPostfix = $iContentId < 0 ? '_rc' : '';

        $sPopupId = $this->getName() . '_notes_' . $iContentId;
        $sPopupTitle = !empty($CNF['T']['txt_cmts_notes_popup_view_title' . $sPostfix]) ? $CNF['T']['txt_cmts_notes_popup_view_title' . $sPostfix] : '_cmt_nts_popup_view_title' . $sPostfix;
        echoJson(['popup' => BxTemplFunctions::getInstance()->popupBox($sPopupId, _t($sPopupTitle), $aCmtsNotes['content'])]);
    }
    
    public function actionNested()
    {
        $sAction = bx_get('a');
        $sMethodName = 'subaction' . ucfirst($sAction);
        if (!method_exists($this, $sMethodName)) {
            return;
        }
        $this->$sMethodName();
    }
    
    public function actionEmbed($iContentId, $sUnitTemplate = '', $sAddCode = '')
    {
        $oTemplate = BxDolTemplate::getInstance();
        
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            $oTemplate->getEmbed(false);

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit_gallery.html';

        $CNF = &$this->_oConfig->CNF;
        
        if(!empty($CNF['OBJECT_VIEWS'])) {
            $oView = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId);
            if($oView && $oView->isEnabled())
                $oView->doView();
        }
        
        $oTemplate->getEmbed($this->_oTemplate->unit($aContentInfo, true, $sUnitTemplate) . $sAddCode);
    }
   
    public function subactionDelete()
    {
        header('Content-type: text/html; charset=utf-8');

        $iNestedId = (int)bx_get('id');
		$sNestedForm = bx_get('s');
        
        $oForm = BxDolForm::getObjectInstance($sNestedForm, $sNestedForm);
        if (!$oForm){
            echo _t('_sys_request_page_not_found_cpt');
            return;
        }

        $aNested = $this->_oDb->getNestedBy(array('type' => 'id', 'id' => $iNestedId, 'key_name' => $oForm->aParams['db']['key']), $oForm->aParams['db']['table']);
        if (empty($aNested)){
            echo _t('_sys_request_page_not_found_cpt');
            return;
        }
        
        $aContentInfo = $this->_oDb->getContentInfoById ($aNested['content_id']); 
        if (!$aContentInfo){
            echo _t('_sys_request_page_not_found_cpt');
            return;
        }

        elseif (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedEdit ($aContentInfo))) {
            echo $sMsg;
        } 
        elseif (!$this->_oDb->deleteNestedById($iNestedId, $oForm->aParams['db']['key'], $oForm->aParams['db']['table'])) {
            echo _t('_sys_txt_error_occured');
        } 
        else {
            echo 'ok';
        }
    }
    
    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetProfiles ()
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }

    // ====== SERVICE METHODS

    public function serviceIsBadgesAvaliable()
    {
        if(!isAdmin() && !$this->_isAdministrator())
            return false;

        $aBadges = BxDolBadges::getInstance()->getData([
            'type' => 'by_module&object', 
            'object_id' => 0, 
            'module' => $this->getName()
        ]);

        return count($aBadges) > 0 ? true : false;
    }

    public function serviceGetSafeServices()
    {
        return array(
            // other
            'ModuleIcon' => '',
            'GetLink' => '',
            // browse
            'GetSearchResultUnit' => '',
            'Browse' => '',
            'BrowseFeatured' => '',
            'BrowseFavorite' => '',
            // forms
            'GetCreatePostForm' => '',
            'EntityEdit' => '',
            'EntityDelete' => '',
            // page blocks
            'EntityTextBlock' => '',
            'EntityInfo' => '',
            'EntityInfoFull' => '',
            'EntityInfoExtended' => '',
            'EntityLocation' => '',
            'EntityComments' => '',
            'EntityAttachments' => '',
            'CategoriesMultiList' => '',
            // menu
            'EntityAllActions' => '',
            'EntityActions' => '',
            'EntitySocialSharing' => '',
            'MyEntriesActions' => '',
        );
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-other Other
     * @subsubsection bx_base_general-module_icon module_icon
     * 
     * @code bx_srv('bx_posts', 'module_icon', [...]); @endcode
     * 
     * Get module icon as CSS class name with FontAwesome icon name class and color class
     * 
     * @see BxBaseModGeneralModule::serviceModuleIcon
     */
    /** 
     * @ref bx_base_general-module_icon "module_icon"
     */
    public function serviceModuleIcon ()
    {
        return isset($this->_oConfig->CNF['ICON']) ? $this->_oConfig->CNF['ICON'] : '';
    }

    public function serviceGetAuthor ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_AUTHOR', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0; 
    }

    public function serviceGetPrivacyView ($iContentId)
    {
        return $this->_getFieldValue('FIELD_ALLOW_VIEW_TO', $iContentId);
    }

    public function serviceGetDateAdded ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_ADDED', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0; 
    }

    public function serviceGetDateChanged ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_CHANGED', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-other Other
     * @subsubsection bx_base_general-get_link get_link
     * 
     * @code bx_srv('bx_posts', 'get_link', [...]); @endcode
     * 
     * Get URL for the specified content.
     * @param $iContentId content id
     * 
     * @see BxBaseModGeneralModule::serviceGetLink
     */
    /** 
     * @ref bx_base_general-get_link "get_link"
     */
    public function serviceGetLink ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['URI_VIEW_ENTRY']))
            return '';

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return '';

        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]));
    }

    public function serviceGetTitle ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_TITLE', $iContentId);
        return $mixedResult !== false ? $mixedResult : '';
    }

    public function serviceGetText ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_TEXT', $iContentId);
        if (false === $mixedResult)
            return '';

        $CNF = &$this->_oConfig->CNF;
        if (!empty($CNF['OBJECT_METATAGS']) && is_string($mixedResult)) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $mixedResult = $oMetatags->metaParse($iContentId, $mixedResult);
        }

        return $mixedResult;
    }
    
    public function serviceGetEmbed ($iContentId)
    {
        $sTitle = $this->_getFieldValue('FIELD_TITLE', $iContentId);
        return $this->_oTemplate->parseHtmlByName('embed.html', [
            'title' => $sTitle,
            'url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'embed/' . $iContentId . '/'
        ]);
    }
    

    public function serviceGetInfo ($iContentId, $bSearchableFieldsOnly = true)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_getFields($iContentId);
        if(empty($aContentInfo))
            return array();

        if(!$bSearchableFieldsOnly)
            return $aContentInfo;

        if(empty($CNF['PARAM_SEARCHABLE_FIELDS']))
            return array();

        $aFields = explode(',', getParam($CNF['PARAM_SEARCHABLE_FIELDS']));
        if(empty($aFields))
            return array();

        $aResult = array();
        foreach($aFields as $sField)
            if(isset($aContentInfo[$sField]))
                $aResult[$sField] = $aContentInfo[$sField];

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-get_search_result_unit get_search_result_unit
     * 
     * @code bx_srv('bx_posts', 'get_search_result_unit', [3,"unit_gallery.html"]); @endcode
     * 
     * Get browsing unit for the specified content
     * @param $iContentId content id
     * @param $sUnitTemplate unit template, such as: unit_full.html, unit_gallery.html, unit_live_search.html
     * 
     * @see BxBaseModGeneralModule::serviceGetSearchResultUnit
     */
    /** 
     * @ref bx_base_general-get_search_result_unit "get_search_result_unit"
     */
    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return '';

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit.html';

        return $this->_oTemplate->unit($aContentInfo, true, $sUnitTemplate);
    }

    public function serviceGetAll ($aParams = array())
    {
        if(empty($aParams) || !is_array($aParams))
            $aParams = array('type' => 'all');

        return $this->_oDb->getEntriesBy($aParams);
    }

    public function serviceGetAllByAuthor ($iProfileId)
    {
        return $this->_oDb->getEntriesByAuthor((int)$iProfileId);
    }

    public function serviceGetSortableFieldsExtended($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_FORM_ENTRY']))
            return array();
        
        $aResult = array();
        
        $aSortableFields = [];
        
        $aDisplays = [];
        if(!empty($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'])) {
            $aDisplays[] = $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'];
        }
        if(!empty($CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'])) {
            $aDisplays[] = $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'];
        }
        
        $aInputsAll = BxDolFormQuery::getFormInputs($CNF['OBJECT_FORM_ENTRY']);    
        foreach($aInputsAll as $aInput){
            $aInputsAll[$aInput['name']] = $aInput;
        }
        
        $aInputs = BxDolFormQuery::getFormInputs($CNF['OBJECT_FORM_ENTRY'],  $aDisplays);
        foreach($aInputs as $aInput){
            $aInputs[$aInput['name']] = $aInput;
            if ($aInput['type'] == 'text'){
                $aSortableFields[] = $aInput['name'];
            }
        }
        
        if(!empty($CNF['FIELD_ADDED']) && isset($aInputsAll[$CNF['FIELD_ADDED']])){
           $aSortableFields[] = $CNF['FIELD_ADDED'];
        }
        
        if(!empty($CNF['FIELD_CHANGED']) && isset($aInputsAll[$CNF['FIELD_CHANGED']])){
           $aSortableFields[] = $CNF['FIELD_CHANGED'];
        }     
        
        foreach($aSortableFields as $sSortableField){
             $aResult[$sSortableField . '_asc'] = [
                'name' => $sSortableField,
                'caption' => $aInputsAll[$sSortableField]['caption'],
                'direction' => 'asc'
            ];
            
            $aResult[$sSortableField . '_desc']  = [
                'name' => $sSortableField,
                'caption' => $aInputsAll[$sSortableField]['caption'],
                'direction' => 'desc'
            ];
        }
        
        return $aResult;
    }
    
    public function serviceGetSearchableFieldsExtended($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_FORM_ENTRY']))
            return array();

        $aResult = array();
        if(!empty($CNF['FIELD_AUTHOR']) && !in_array($CNF['FIELD_AUTHOR'], $this->_aSearchableNamesExcept))
            $aResult[$CNF['FIELD_AUTHOR']] = array(
                'type' => 'text_auto', 
                'caption' => $CNF['T']['form_field_author'],
                'info' => '',
            	'value' => '',
                'values' => '',
                'pass' => ''
            );

        $aInputs = array();
        if(!empty($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'])) {
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);

            $aInputs = array_merge($aInputs, $oForm->aInputs);
        }
        if(!empty($CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'])) {
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'], $this->_oTemplate);

            $aInputs = array_merge($aInputs, $oForm->aInputs);
        }

        if(!empty($aInputsAdd) && is_array($aInputsAdd))
            $aInputs = array_merge($aInputs, $aInputsAdd);

        foreach($aInputs as $aInput){
            if(in_array($aInput['type'], BxDolSearchExtended::$SEARCHABLE_TYPES) && !in_array($aInput['name'], $this->_aSearchableNamesExcept)) {
                $aField = array(
                    'type' => $aInput['type'], 
                    'caption_system' => $aInput['caption_system_src'],
                    'caption' => $aInput['caption_src'],
                    'info' => $aInput['info_src'],
                    'value' => !empty($aInput['value']) ? $aInput['value'] : '',
                    'values' => !empty($aInput['values_src']) ? $aInput['values_src'] : '',
                    'pass' => !empty($aInput['db']['pass']) ? $aInput['db']['pass'] : '',
                );

                if(isset($aInput['search_type']))
                    $aField['search_type'] = $aInput['search_type'];

                if(isset($aInput['search_operator']))
                    $aField['search_operator'] = $aInput['search_operator'];

                $aResult[$aInput['name']] = $aField;
            }
        }
        return $aResult;
    }

    public function serviceGetSearchResultExtended($aParams, $iStart = 0, $iPerPage = 0, $bFilterMode = false)
    {
        if((empty($aParams) || !is_array($aParams)) && !$bFilterMode)
            return array();

        return $this->_oDb->getEntriesBy(array('type' => 'search_ids', 'search_params' => $aParams, 'start' => $iStart, 'per_page' => $iPerPage));
    }

    public function serviceGetSearchableFields ($aInputsAdd = array())
    {
        $CNF = $this->_oConfig->CNF;

        if(!isset($CNF['PARAM_SEARCHABLE_FIELDS']) || !isset($CNF['OBJECT_FORM_ENTRY']) || !isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']))
            return array();

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);
        if(!$oForm)
            return array();

        $aInputs = $oForm->aInputs;
        if(!empty($aInputsAdd) && is_array($aInputsAdd))
            $aInputs = array_merge($aInputs, $aInputsAdd);

        $aTextTypes = array('text', 'textarea');
        $aTextFields = array();
        foreach($aInputs as $r)
            if(in_array($r['type'], $aTextTypes))
                $aTextFields[$r['name']] = $r['caption'];

        return $aTextFields;
    }

    public function serviceManageTools($sType = 'common')
    {
        if(!isLogged())
            return '';

        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getGridObject($sType));
        if(!$oGrid)
            return '';

        $CNF = &$this->_oConfig->CNF;

        $mixedMenu = '';
        if(BxDolAcl::getInstance()->isMemberLevelInSet(192)) {
            $oPermalink = BxDolPermalinks::getInstance();

            $aMenuItems = array();
            if(!empty($CNF['OBJECT_GRID_COMMON']) && !empty($CNF['T']['menu_item_manage_my']))
                $aMenuItems[] = array('id' => 'manage-common', 'name' => 'manage-common', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_MANAGE_COMMON']), 'target' => '_self', 'title' => _t($CNF['T']['menu_item_manage_my']), 'active' => 1);
            if(!empty($CNF['OBJECT_GRID_ADMINISTRATION']) && !empty($CNF['T']['menu_item_manage_all']))
                $aMenuItems[] = array('id' => 'manage-administration', 'name' => 'manage-administration', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_MANAGE_ADMINISTRATION']), 'target' => '_self', 'title' => _t($CNF['T']['menu_item_manage_all']), 'active' => 1);

            if(count($aMenuItems) > 1) {
                $mixedMenu = new BxTemplMenu(array(
                    'template' => 'menu_block_submenu_ver.html', 
                    'menu_items' => $aMenuItems
                ), $this->_oTemplate);
                $mixedMenu->setSelected($this->_aModule['name'], 'manage-' . $sType);
            }
        }

        if(!empty($CNF['OBJECT_MENU_SUBMENU']) && isset($CNF['URI_MANAGE_COMMON'])) {
            BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU'])->setSelected($this->_aModule['name'], $CNF['URI_MANAGE_COMMON']);
        }

        $this->_oTemplate->addCss(array('manage_tools.css'));
        $this->_oTemplate->addJs(array('manage_tools.js'));
        $this->_oTemplate->addJsTranslation(array('_sys_grid_search'));
        return array(
            'content' => $this->_oTemplate->getJsCode('manage_tools', array('sObjNameGrid' => $this->_oConfig->getGridObject($sType))) . $oGrid->getCode(),
            'menu' => $mixedMenu
        );
    }

    public function serviceGetMenuAddonManageTools()
    {
    	return 0;
    }

    public function serviceGetMenuAddonManageToolsProfileStats()
    {
    	return 0;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-browse browse
     * 
     * @code bx_srv('bx_posts', 'browse', [...]); @endcode
     * @code {{~bx_posts:browse[{"mode":"author", "params":{"author":"10"}}]~}} @endcode
     * 
     * Universal browse method.
     * @param $aParams custom browse params, possible params are the following:
     *  - mode - browse mode, such as 'recent', 'featured', etc
     *  - params - custom params to browse method, for example 'unit_view' can be passed here
     *  - design_box - design box style, @see BxBaseFunctions::DesignBoxContent 
     *  - empty_message - display or not "empty" message when there is no content
     *  - ajax_paginate - use AJAX paginate or not
     * 
     * @see BxBaseModGeneralModule::serviceBrowse
     */
    /** 
     * @ref bx_base_general-browse "browse"
     */
    public function serviceBrowse ($aParams = array())
    {
        unset($aParams['params']['condition']);
        return $this->serviceBrowseWithCondition ($aParams);
    }

    public function serviceBrowseWithCondition ($aParams = array())
    {
        $aDefaults = array (
            'mode' => 'recent',
            'params' => false,
            'design_box' => BX_DB_PADDING_DEF,
            'empty_message' => false,
            'ajax_paginate' => true,
            'class_search_result' => 'SearchResult'
        );
        $aParams = array_merge($aDefaults, $aParams);
        return $this->_serviceBrowse ($aParams['mode'], $aParams['params'], $aParams['design_box'], $aParams['empty_message'], $aParams['ajax_paginate'], $aParams['class_search_result']);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-browse_featured browse_featured
     * 
     * @code bx_srv('bx_posts', 'browse_featured', [...]); @endcode
     * 
     * Display featured entries
     * @param $sUnitView browsing unity view, 
     *                   such as: full, extended, gallery, showcase
     * @param $bEmptyMessage display or not "empty" message when there is no content
     * @param $bAjaxPaginate use AJAX paginate or not
     * 
     * @see BxBaseModGeneralModule::serviceBrowseFeatured
     */
    /** 
     * @ref bx_base_general-browse_featured "browse_featured"
     */
    public function serviceBrowseFeatured ($sUnitView = false, $bEmptyMessage = false, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('featured', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }
	
    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-browse_favorite browse_favorite
     * 
     * @code bx_srv('bx_posts', 'browse_favorite', [...]); @endcode
     * 
     * Display entries which were added to favorite list by some member
     * @param $iProfileId profile ID
     * @param $aParams additional browse params, see BxBaseModGeneralModule::serviceBrowse
     * 
     * @see BxBaseModGeneralModule::serviceBrowseFavorite BxBaseModGeneralModule::serviceBrowse
     */
    /** 
     * @ref bx_base_general-browse_favorite "browse_favorite"
     */
    public function serviceBrowseFavorite ($iProfileId = 0, $aParams = array())
    {
        $oProfile = null;
        if((int)$iProfileId)
            $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile && bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));
        if(!$oProfile)
            $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return '';

        $bEmptyMessage = true;
        if(isset($aParams['empty_message'])) {
            $bEmptyMessage = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }
        
        if(bx_get('list_id') !== false && !isset($aParams['list_id']))
            $aParams['list_id'] = (int) bx_get('list_id');

        return $this->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, $bEmptyMessage);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-browse_favorite_lists browse_favorite_lists
     * 
     * @code bx_srv('bx_posts', 'browse_favorite_lists', [...]); @endcode
     * 
     * Display entries which were added to favorite lists grouped by lists by some member
     * @param $iProfileId profile ID
     * @param $aParams additional browse params, see BxBaseModGeneralModule::serviceBrowse
     * 
     * @see BxBaseModGeneralModule::serviceBrowseFavoriteLists BxBaseModGeneralModule::serviceBrowse
     */
    /** 
     * @ref bx_base_general-browse_favorite_lists "browse_favorite_lists"
     */
    public function serviceBrowseFavoriteLists ($iProfileId = 0, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
        
        $oProfile = null;
        if((int)$iProfileId)
            $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile && bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));
        if(!$oProfile)
            $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return '';
        
        $iStart = (bx_get('list_start') !== false ? (int)bx_get('list_start') : 0);
        $iPerPage = (int)getParam($CNF['PARAM_PER_PAGE_FOR_FAVORITES_LISTS']);
        
        return $this->_oTemplate->getFavoriteList($oProfile, $iStart, $iPerPage, $aParams);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-browsing Browsing
     * @subsubsection bx_base_general-browse_favorite_list_actions browse_favorite_list_actions
     * 
     * @code bx_srv('bx_posts', 'browse_favorite_list_actions', [...]); @endcode
     * 
     * Display menu actions for favorite lists
     * 
     * @see BxBaseModGeneralModule::serviceFavoritesListActions
     */
    /** 
     * @ref bx_base_general-browse_favorite_list_actions "browse_favorite_list_actions"
     */
    public function serviceFavoritesListActions()
    {
        $iListId = 0;
        if(bx_get('list_id') !== false)
            $iListId = (int) bx_get('list_id');        

        $oProfile = null;
        if(bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));
        if(!$oProfile)
            return false;

        $CNF = &$this->_oConfig->CNF;
        $oFavorite = BxDolFavorite::getObjectInstance($CNF['OBJECT_FAVORITES'], 0, true);

        $aList = $oFavorite->getQueryObject()->getList(array('type' => 'id', 'list_id' => $iListId));
        if($iListId != 0 && (empty($aList) || !is_array($aList)))
            return false;

        $sRv = '';
        if (!empty($aList) && $oFavorite->isAllowedEditList($aList['author_id'])){  
            $aMarkers = array(
                'js_object' => $oFavorite->getJsObjectName(),
                'list_id' => $iListId,
            );

            $oMenu = BxDolMenu::getObjectInstance('sys_favorite_list');

            $oMenu->addMarkers($aMarkers);
            $sMenu = $oMenu->getCode();

            $sRv .= $sMenu . $oFavorite->getJsScript();
        }

        $aMarkers = array(
            'id' => $iListId,
            'module' => $this->_aModule['name'],
            'url' => $this->_getFavoriteListUrl($iListId, $oProfile->id()),
            'title' => $iListId > 0 ? $aList['title'] : _t('_sys_txt_default_favorite_list')
        );

        $oMenu = BxDolMenu::getObjectInstance('sys_social_sharing');
        $oMenu->addMarkers($aMarkers);
        $sRv .= $sMenu = $oMenu->getCode();
        return $sRv;
    }
     
     /**
    * @page service Service Calls
    * @section bx_base_general Base General
    * @subsection bx_base_general-menu Menu
    * @subsubsection bx_base_general-favorites_list_info favorites_list_info
    * 
    * @code bx_srv('bx_posts', 'favorites_list_info', [...]); @endcode
    * 
    * Favorites list info
    * @param $aParams array with additional custom params 
    *                 which may overwrite some default values
    * 
    * @see BxBaseModGeneralModule::serviceFavoritesListInfo
    */
    /** 
    * @ref bx_base_general-favorites_list_info "favorites_list_info"
    */
    public function serviceFavoritesListInfo($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $iListId = 0;
        if(bx_get('list_id') !== false)
            $iListId = (int) bx_get('list_id');       

        $oFavorite = BxDolFavorite::getObjectInstance($CNF['OBJECT_FAVORITES'], 0, true);
        $aList = $oFavorite->getQueryObject()->getList(array('type' => 'id', 'list_id' => $iListId));
        if($iListId != 0 && (empty($aList) || !is_array($aList)))
            return false;

        $oProfile = null;
        if(bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));

        if(!$oProfile)
            return false;

        return $this->_oTemplate->getFavoritesListInfo($aList, $oProfile);
    }
    
    /**
     * Display entries posted into particular context
     * @return HTML string
     */
    public function serviceBrowseContext ($iProfileId = 0, $aParams = array())
    {
        return $this->_serviceBrowseWithParam ('context', 'profile_id', $iProfileId, $aParams);
    }
    
    public function _serviceBrowseWithParam ($sParamName, $sParamGet, $sParamVal, $aParams = array())
    {
        if(!$sParamVal)
            $sParamVal = bx_process_input(bx_get($sParamGet), BX_DATA_INT);
        if(!$sParamVal)
            return '';

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

        return $this->_serviceBrowse ($sParamName, array_merge(array($sParamName => $sParamVal), $aParams), BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    public function serviceFormsHelper ()
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        return new $sClass($this);
    }

	/**
     * Add entry using provided fields' values.
     * @return array with result: 'code' is 0 on success or non-zero on error, 'message' is error message in case of error, 'content' is content info array in case of success
     */
    public function serviceEntityAdd ($iProfile, $aValues, $sDisplay = false)
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->addData($iProfile, $aValues, $sDisplay);
    }

	/**
     * Perform redirect after content creation
     * @return nothing, rediret header is sent
     */    
    public function serviceRedirectAfterAdd($aContentInfo)
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        $oFormsHelper->redirectAfterAdd($aContentInfo);
    }

    /**
     * Get form object for add, edit, view or delete the content 
     * @param $sType 'add', 'edit', 'view' or 'delete'
     * @param $aParams optional array with parameters(display name, etc)
     * @return form object or false on error
     */
    public function serviceGetObjectForm ($sType, $aParams = array())
    {
        if(!in_array($sType, array('add', 'edit', 'view', 'delete')))
            return false;

        $mixedContextId = isset($aParams['context_id']) ? $aParams['context_id'] : false;

        $bContext = $mixedContextId !== false;
        if($bContext && ($oContextProfile = BxDolProfile::getInstance(abs($mixedContextId))) !== false)
            if($oContextProfile->checkAllowedPostInProfile() !== CHECK_ACTION_RESULT_ALLOWED)
                return false;

        $CNF = &$this->_oConfig->CNF;

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);

        $sParamsKey = 'ajax_mode';
        if(isset($aParams[$sParamsKey]) && (bool)$aParams[$sParamsKey] === true)
            $oFormsHelper->setAjaxMode((bool)$aParams[$sParamsKey]);

        $sParamsKey = 'absolute_action_url';
        if(isset($aParams[$sParamsKey]) && (bool)$aParams[$sParamsKey] === true)
            $oFormsHelper->setAbsoluteActionUrl((bool)$aParams[$sParamsKey]);

        $sDisplay = !empty($aParams['display']) ? $aParams['display'] : false;

        $sFunc = 'getObjectForm' . ucfirst($sType);
        $oForm = $oFormsHelper->$sFunc($sDisplay);

        $sKey = 'FIELD_ALLOW_VIEW_TO';
        if(!empty($CNF[$sKey]) && !empty($oForm->aInputs[$CNF[$sKey]]) && (!$bContext || $mixedContextId < 0)) {
            $bContextOwner = $bContext && abs($mixedContextId) == (int)bx_get_logged_profile_id();

            if(!$bContext || $bContextOwner) {
                $iGc = 0;
                $iKeyGh = false;
                foreach($oForm->aInputs[$CNF[$sKey]]['values'] as $iKey => $aValue) {
                    if(isset($aValue['type']) && in_array($aValue['type'], array('group_header', 'group_end'))) {
                        if($iKeyGh !== false && $iGc == 0) {
                            unset($oForm->aInputs[$CNF[$sKey]]['values'][$iKeyGh]);
                            $iKeyGh = false;

                            if($aValue['type'] == 'group_end')
                                unset($oForm->aInputs[$CNF[$sKey]]['values'][$iKey]);
                        }

                        if($aValue['type'] == 'group_header') {
                            $iGc = 0;
                            $iKeyGh = $iKey;
                        }

                        continue;
                    }

                    //--- Show 'Public' privacy group only in Public post form. 
                    if(!$bContext && $aValue['key'] == BX_DOL_PG_ALL) {
                        $iGc += 1;
                        continue;
                    }

                    //--- Show a default privacy groups in Profile (for Owner) post form.
                    if($bContextOwner && ((int)$aValue['key'] >= 0 || $mixedContextId == $aValue['key'])) {
                        $iGc += 1;
                        continue;
                    }

                    unset($oForm->aInputs[$CNF[$sKey]]['values'][$iKey]);
                }
            }
            else {
                $oForm->aInputs[$CNF[$sKey]]['value'] = $mixedContextId;
                $oForm->aInputs[$CNF[$sKey]]['type'] = 'hidden';
            }
        }

        bx_alert('system', 'get_object_form', 0, 0, array(
            'module' => $this->_oConfig->getName(),
            'type' => $sType,
            'params' => $aParams,
            'form' => &$oForm
        ));

        return $oForm;
    }

    /**
     *
     *
     * Create entry form
     * @return HTML string
     */
    public function serviceEntityCreate ($sParams = false)
    {
        $bParamsArray = is_array($sParams);

        $sDisplay = is_string($sParams) ? $sParams : false;
        if($bParamsArray && !empty($sParams['display']))
            $sDisplay = $sParams['display'];

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        if($bParamsArray && isset($sParams['dynamic_mode']))
            $oFormsHelper->setDynamicMode($sParams['dynamic_mode']);

        $mixedResult = $oFormsHelper->addDataForm($sDisplay);
        if(isset($mixedResult['_dt']) && $mixedResult['_dt'] == 'json') {
            echoJson($mixedResult);
            exit;
        }

        return $mixedResult;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-forms Forms
     * @subsubsection bx_base_general-get_create_post_form get_create_post_form
     * 
     * @code bx_srv('bx_posts', 'get_create_post_form'); @endcode
     * 
     * Get content creation form
     * @param $aParams additional parameters array: 
     *                 context_id, ajax_mode, absolute_action_url, displays
     * 
     * @see BxBaseModGeneralModule::serviceGetCreatePostForm
     */
    /** 
     * @ref bx_base_general-get_create_post_form "get_create_post_form"
     */
    public function serviceGetCreatePostForm($aParams = [])
    {
    	$aParams = array_merge($this->_aFormParams, $aParams);

        if($aParams['context_id'] !== false && ($aContextInfo = BxDolProfileQuery::getInstance()->getInfoById(abs($aParams['context_id']))))
            if(bx_srv($aContextInfo['type'], 'check_allowed_post_in_profile', [$aContextInfo['content_id'], $this->getName()]) !== CHECK_ACTION_RESULT_ALLOWED)
                return '';

    	$oForm = $this->serviceGetObjectForm('add', $aParams);
    	if(!$oForm)
            return '';

    	return $this->serviceEntityCreate($aParams);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-forms Forms
     * @subsubsection bx_base_general-entity_edit entity_edit
     * 
     * @code bx_srv('bx_posts', 'entity_edit', [...]); @endcode
     * 
     * Get content edit form
     * @param $iContentId content ID
     * @param $sDisplay optional form display name
     * 
     * @see BxBaseModGeneralModule::serviceEntityEdit
     */
    /** 
     * @ref bx_base_general-entity_edit "entity_edit"
     */
    public function serviceEntityEdit ($iContentId = 0, $sDisplay = false)
    {
        $mixedResult = $this->_serviceEntityForm ('editDataForm', $iContentId, $sDisplay);
        if(!bx_is_api()) 
            return $mixedResult;

        $aResult = [];
        if(is_a($mixedResult, 'BxTemplFormView'))
            $aResult = ['id' => 1, 'type' => 'form', 'data' => $mixedResult->getCodeAPI(), 'request' => ['url' => '/api.php?r=' . $this->_aModule['name'] . '/entity_edit', 'immutable' => true]];
        else
            $aResult = $mixedResult;

        return [
            $aResult
        ];
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-forms Forms
     * @subsubsection bx_base_general-entity_delete entity_delete
     * 
     * @code bx_srv('bx_posts', 'entity_delete', [...]); @endcode
     * 
     * Get content delete form
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityDelete
     */
    /** 
     * @ref bx_base_general-entity_delete "entity_delete"
     */
    public function serviceEntityDelete ($iContentId = 0)
    {
        $mixedResult = $this->_serviceEntityForm ('deleteDataForm', $iContentId);
        if(!bx_is_api()) 
            return $mixedResult;

        $aResult = [];
        if(is_a($mixedResult, 'BxTemplFormView'))
            $aResult = ['id' => 1, 'type' => 'form', 'data' => $mixedResult->getCodeAPI(), 'request' => ['url' => '/api.php?r=' . $this->_aModule['name'] . '/entity_delete', 'immutable' => true]];
        else
            $aResult = $mixedResult;

        return [
            $aResult
        ];
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_text_block entity_text_block
     * 
     * @code bx_srv('bx_posts', 'entity_text_block', [...]); @endcode
     * 
     * Get content text/description
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityTextBlock
     */
    /** 
     * @ref bx_base_general-entity_text_block "entity_text_block"
     */
    public function serviceEntityTextBlock ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('viewDataEntry', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_info entity_info
     * 
     * @code bx_srv('bx_posts', 'entity_info', [...]); @endcode
     * 
     * Get content info
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityInfo
     */
    /** 
     * @ref bx_base_general-entity_info "entity_info"
     */
    public function serviceEntityInfo ($iContentId = 0, $sDisplay = false)
    {
        return $this->_serviceEntityForm ('viewDataForm', $iContentId, $sDisplay);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_info_full entity_info_full
     * 
     * @code bx_srv('bx_posts', 'entity_info_full', [...]); @endcode
     * 
     * Get full content info
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityInfoFull
     */
    /** 
     * @ref bx_base_general-entity_info_full "entity_info_full"
     */
	public function serviceEntityInfoFull ($iContentId = 0)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sDisplayName = 'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL';
        return $this->_serviceEntityForm ('viewDataForm', $iContentId, !empty($CNF[$sDisplayName]) ? $CNF[$sDisplayName] : false);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_info_extended entity_info_extended
     * 
     * @code bx_srv('bx_posts', 'entity_info_extended', [...]); @endcode
     * 
     * Get extended content info
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityInfoExtended
     */
    /** 
     * @ref bx_base_general-entity_info_extended "entity_info_extended"
     */
	public function serviceEntityInfoExtended ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryInfo', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_location entity_location
     * 
     * @code bx_srv('bx_posts', 'entity_location', [...]); @endcode
     * 
     * Get content location
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityLocation
     */
    /** 
     * @ref bx_base_general-entity_location "entity_location"
     */
    public function serviceEntityLocation ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        return $this->_oTemplate->entryLocation ($iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_comments entity_comments
     * 
     * @code bx_srv('bx_posts', 'entity_comments', [...]); @endcode
     * 
     * Get content comments
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityComments
     */
    /** 
     * @ref bx_base_general-entity_comments "entity_comments"
     */
    public function serviceEntityComments ($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_COMMENTS']))
            return '';

        return $this->_entityComments($CNF['OBJECT_COMMENTS'], $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_attachments entity_attachments
     * 
     * @code bx_srv('bx_posts', 'entity_attachments', [...]); @endcode
     * 
     * Get content attachments
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityAttachments
     */
    /** 
     * @ref bx_base_general-entity_attachments "entity_attachments"
     */
    public function serviceEntityAttachments ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAttachments', $iContentId);
    }
    
    /**
     * Delete content entry
     * @param $iContentId content id 
     * @return error message or empty string on success
     */
    public function serviceDeleteEntity ($iContentId, $sFuncDelete = 'deleteData')
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_oConfig->getClassPrefix() . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFuncDelete($iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-menu Menu
     * @subsubsection bx_base_general-my_entries_actions my_entries_actions
     * 
     * @code bx_srv('bx_posts', 'my_entries_actions', [...]); @endcode
     * 
     * My entries actions menu
     * @param $iProfileId profiles ID, 
     *        if omitted it try to get it from 'profile_id' GET param
     * 
     * @see BxBaseModGeneralModule::serviceMyEntriesActions
     */
    /** 
     * @ref bx_base_general-my_entries_actions "my_entries_actions"
     */
    public function serviceMyEntriesActions ($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']))
            return false;

        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId || !($oProfile = BxDolProfile::getInstance($iProfileId)))
            return false;

        if ($iProfileId != $this->_iProfileId)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-menu Menu
     * @subsubsection bx_base_general-entity_all_actions entity_all_actions
     * 
     * @code bx_srv('bx_posts', 'entity_all_actions', [...]); @endcode
     * 
     * Entry actions and social sharing actions
     * @param $mixedContent content ID
     * @param $aParams additional params
     * 
     * @see BxBaseModGeneralModule::serviceEntityAllActions
     */
    /** 
     * @ref bx_base_general-entity_all_actions "entity_all_actions"
     */
    public function serviceEntityAllActions ($mixedContent = false, $aParams = array())
    {
        $mixedResult = $this->getEntryAllActions($mixedContent, $aParams);

        if(bx_is_api())
            return [['id' => 1, 'type' => 'actions', 'data' => $mixedResult]];
        else
            return $this->_oTemplate->entryAllActions($mixedResult);
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-menu Menu
     * @subsubsection bx_base_general-entity_actions entity_actions
     * 
     * @code bx_srv('bx_posts', 'entity_actions', [...]); @endcode
     * 
     * Entry actions, without social sharing actions
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityActions
     */
    /** 
     * @ref bx_base_general-entity_actions "entity_actions"
     */
    public function serviceEntityActions ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-entity_reports entity_reports
     * 
     * @code bx_srv('bx_posts', 'entity_reports', [...]); @endcode
     * 
     * Get content reports
     * @param $iContentId content ID
     * 
     * @see BxBaseModGeneralModule::serviceEntityReports
     */
    /** 
     * @ref bx_base_general-entity_reports "entity_reports"
     */
    public function serviceEntityReports ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        list($iContentId, $aContentInfo) = $mixedContent;
        
        if($iContentId === false)
            return false;
        
        $CNF = &$this->_oConfig->CNF;
        
        if (!isset($CNF['OBJECT_REPORTS']) || !isset($CNF['OBJECT_NOTES']))
            return false;

        if(!$aContentInfo || !(BxDolAcl::getInstance()->isMemberLevelInSet(192) || (isset($CNF['FIELD_AUTHOR']) && bx_get_logged_profile_id() == $aContentInfo[$CNF['FIELD_AUTHOR']])))
            return false;

        $oReport = BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $iContentId, true);
        if(!$oReport)
            return false;

        return $oReport->getReportedByWithComments($CNF['OBJECT_NOTES']);
    }
    
     /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-reports_count_by_status reports_count_by_status
     * 
     * @code bx_srv('bx_posts', 'reports_count_by_status', [...]); @endcode
     * 
     * Get number of reports with specified status
     * @param $iStatus status ID
     * 
     * @see BxBaseModGeneralModule::serviceReportsCountByStatus
     */
    /** 
     * @ref bx_base_general-entity_reports "entity_reports"
     */
    public function serviceReportsCountByStatus ($iStatus)
    {
        $CNF = &$this->_oConfig->CNF;
        
        if (!isset($CNF['OBJECT_REPORTS']) || !isset($CNF['OBJECT_NOTES']))
            return false;
        
        $oReport = BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], 0, false);
        return $oReport->getCountByStatus($iStatus);
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-menu Menu
     * @subsubsection bx_base_general-entity_social_sharing entity_social_sharing
     * 
     * @code bx_srv('bx_posts', 'entity_social_sharing', [...]); @endcode
     * 
     * Entry social sharing actions
     * @param $mixedContent content ID or array with integer content ID and 
     *                      array with content info, or false
     * @param $aParams array with additional custom params 
     *                 which may overwrite some default values
     * 
     * @see BxBaseModGeneralModule::serviceEntitySocialSharing
     */
    /** 
     * @ref bx_base_general-entity_social_sharing "entity_social_sharing"
     */
    public function serviceEntitySocialSharing($mixedContent = false, $aParams = array())
    {
        if(!empty($mixedContent)) {
            if(!is_array($mixedContent))
               $mixedContent = array((int)$mixedContent, array());
        }
        else {
            $mixedContent = $this->_getContent();
            if($mixedContent === false)
                return false;
        }

        list($iContentId, $aContentInfo) = $mixedContent;

        $CNF = &$this->_oConfig->CNF;

        $sUri = !empty($aParams['uri']) ? $aParams['uri'] : '';
        if(empty($sUri) && !empty($CNF['URI_VIEW_ENTRY']))
            $sUri = $CNF['URI_VIEW_ENTRY'];

        $sUrl = !empty($sUri) ? BxDolPermalinks::getInstance()->permalink('page.php?i=' . $sUri . '&id=' . $iContentId) : '';

        $sTitle = !empty($aParams['title']) ? $aParams['title'] : '';
        if(empty($sTitle) && !empty($aContentInfo[$CNF['FIELD_TITLE']]))
            $sTitle = $aContentInfo[$CNF['FIELD_TITLE']];

        $aMarkers = array(
            'id' => $iContentId,
            'module' => $this->_aModule['name'],
            'url' => bx_absolute_url($sUrl),
            'title' => $sTitle,
        );

        $iIdThumb = !empty($aParams['id_thumb']) ? (int)$aParams['id_thumb'] : 0;
        if(empty($iIdThumb) && !empty($CNF['FIELD_THUMB']) && !empty($aContentInfo[$CNF['FIELD_THUMB']]))
            $iIdThumb = (int)$aContentInfo[$CNF['FIELD_THUMB']];

        if ($iIdThumb) {
            $sTranscoder = !empty($aParams['object_transcoder']) ? $aParams['object_transcoder'] : '';
            $sStorage = !empty($aParams['object_storage']) ? $aParams['object_storage'] : '';
            if(empty($sStorage) && !empty($CNF['OBJECT_STORAGE']))
                $sStorage = $CNF['OBJECT_STORAGE'];

            if(!empty($sTranscoder))
                $o = BxDolTranscoder::getObjectInstance($sTranscoder);
            else if(!empty($sStorage))
                $o = BxDolStorage::getObjectInstance($sStorage);

            $sImgUrl = $o ? $o->getFileUrlById($iIdThumb) : '';
            if($sImgUrl)
                $aMarkers['img_url'] = $sImgUrl;
        }

        $oMenu = BxDolMenu::getObjectInstance('sys_social_sharing');
        $oMenu->addMarkers($aMarkers);
        $sMenu = $oMenu->getCode();

        if(empty($sMenu))
            return '';

        return $this->_oTemplate->parseHtmlByName('entry-share.html', array(
            'menu' => $sMenu
        ));
    }
    
    /**
     * Entry context block
     */
    public function serviceEntityContext ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryContext', $iContentId);
    }

    /**
     * Blocks for lables tree browsing
     */
    public function serviceGetLablesTree()
    {
		$aBreadcrumbs = array();
		$this->_getLablesBreadcrumbsArray(bx_get('label'), $aBreadcrumbs);
		return $this->_getLablesTreeLevel($aBreadcrumbs);
	}	
    
    public function serviceGetLablesBreadcrumbs()
    {
		if (bx_get('label')){
            $aBreadcrumbs = array();
            $this->_getLablesBreadcrumbsArray(bx_process_input(bx_get('label'), BX_DATA_INT), $aBreadcrumbs);
            $aLabels = array();
            foreach (array_reverse($aBreadcrumbs) as $aLabel){
                $aLabels[] = array(
                   'value' => $aLabel['value'], 
                   'url' => $this->_getLablesBrowseUrl($aLabel['id'])
               );
            }
            
            return $this->_oTemplate->parseHtmlByName('labels_breadcrumbs.html', array('bx_repeat:items' => $aLabels));
		}
        return '';
	}
    
    public function serviceBrowseByLabel()
    {   
        $CNF = &$this->_oConfig->CNF;
        if (empty($CNF['OBJECT_METATAGS']))
            return '';
        
		$sMode = 'recent';
		if(bx_srv('system', 'is_module_content', [$this->_aModule['name']]))
		   $sMode = 'public';
		   
		$sClassSearchResult ='SearchResult';
		
		bx_import($sClassSearchResult, $this->_aModule['name']);
        $sClass = $this->_aModule['class_prefix'] . $sClassSearchResult;
		
        $o = new $sClass($sMode, false);
		$o->setDesignBoxTemplateId(BX_DB_PADDING_DEF);
        $o->setDisplayEmptyMsg(true);
        $o->setAjaxPaginate(false);
        $o->setUnitParams(array('context' => $sMode));
		
		if (bx_get('label')){
			$iLabelId = (int)bx_get('label');
			$oLabel = BxDolLabel::getInstance();
			$aLabel = $oLabel->getLabels(array('type' => 'id', 'id' => $iLabelId));
			$oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
			$sTmp =$oMetatags->keywordsSetSearchCondition($o, $aLabel['value']);
		}
		
        if ($o->isError)
            return '';

        if ($s = $o->processing())
            return $s;
        else
            return '';
	}
    
    public function serviceBrowseByCategories($sUnitView, $bEmptyMessage, $bAjaxPaginate, $sMode, $iPerPage)
    {   
        $CNF = &$this->_oConfig->CNF;
        $sClassSearchResult ='SearchResult';
		
		bx_import($sClassSearchResult, $this->_aModule['name']);
        $sClass = $this->_aModule['class_prefix'] . $sClassSearchResult;
        $o = new $sClass($sMode, array('unit_view' => $sUnitView, 'paginate' => array('perPage' => 10, 'start' => 0, 'num' => 11)));
		$o->setDesignBoxTemplateId(BX_DB_PADDING_DEF);
        $o->setAjaxPaginate($bAjaxPaginate);
        $o->setCategoryObject('multi');
        
        $aCategoriesOutput = array();
		$aCategories = BxDolCategories::getInstance()->getData(array('type' => 'by_module_with_num', 'module' => $this->_aModule['name']));
        
        foreach($aCategories as $aCategory){
            if ($aCategory['num'] > 0){
                $o->setCustomSearchCondition(array('keyword' => $aCategory['value']));
                $o->setPaginatePerPage(2);
                if (!$o->isError){
                    $aResult = $o->processing();
                    if ($aResult && $aResult['content'] != '')
                        $aCategoriesOutput[] =  array('name' => _t($aCategory['value']), 'url' => '', 'content' => $aResult['content']);
                }
            }
        }

        return $this->_oTemplate->parseHtmlByName('browse_by_categories.html', array(
            'bx_repeat:categories' => $aCategoriesOutput,
        ));

	}
    
    /**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

    	$sEventPrivacy = $sModule . '_allow_view_event_to';
        if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
            $sEventPrivacy = '';

        $aResult = [
            'handlers' => [
                ['group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_notifications_post', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
                ['group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'],
                ['group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted'],

                ['group' => $sModule . '_object_approved', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'approved', 'module_name' => $sModule, 'module_method' => 'get_notifications_post_approved', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],

                ['group' => $sModule . '_comment', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
                ['group' => $sModule . '_comment', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'commentRemoved'],

                ['group' => $sModule . '_reply', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'replyPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_reply', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
                ['group' => $sModule . '_reply', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'replyRemoved'],

                ['group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
                ['group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'],

                ['group' => $sModule . '_reaction', 'type' => 'insert', 'alert_unit' => $sModule . '_reactions', 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_reaction', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
                ['group' => $sModule . '_reaction', 'type' => 'delete', 'alert_unit' => $sModule . '_reactions', 'alert_action' => 'undoVote'],

                ['group' => $sModule . '_score_up', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVoteUp', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_up', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],

                ['group' => $sModule . '_score_down', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVoteDown', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_down', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],

                //--- Moderation related: For 'admins'.
                ['group' => $sModule . '_object_pending_approval', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'pending_approval', 'module_name' => $sModule, 'module_method' => 'get_notifications_post_pending_approval', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy],
            ],
            'settings' => [
                ['group' => 'content', 'unit' => $sModule, 'action' => 'added', 'types' => ['follow_member', 'follow_context']],
                ['group' => 'content_updated', 'unit' => $sModule, 'action' => 'approved', 'types' => ['personal']],
                ['group' => 'comment', 'unit' => $sModule, 'action' => 'commentPost', 'types' => ['personal', 'follow_member', 'follow_context']],
                ['group' => 'reply', 'unit' => $sModule, 'action' => 'replyPost', 'types' => ['personal']],
                ['group' => 'vote', 'unit' => $sModule, 'action' => 'doVote', 'types' => ['personal', 'follow_member', 'follow_context']],
                ['group' => 'vote', 'unit' => $sModule . '_reactions', 'action' => 'doVote', 'types' => ['personal', 'follow_member', 'follow_context']],
                ['group' => 'score_up', 'unit' => $sModule, 'action' => 'doVoteUp', 'types' => ['personal', 'follow_member', 'follow_context']],
                ['group' => 'score_down', 'unit' => $sModule, 'action' => 'doVoteDown', 'types' => ['personal', 'follow_member', 'follow_context']],

                //--- Moderation related: For 'admins'.
                ['group' => 'action_required', 'unit' => $sModule, 'action' => 'pending_approval', 'types' => ['personal']],
            ],
            'alerts' => [
                ['unit' => $sModule, 'action' => 'added'],
                ['unit' => $sModule, 'action' => 'edited'],
                ['unit' => $sModule, 'action' => 'deleted'],

                ['unit' => $sModule, 'action' => 'approved'],

                ['unit' => $sModule, 'action' => 'commentPost'],
                ['unit' => $sModule, 'action' => 'commentRemoved'],

                ['unit' => $sModule, 'action' => 'replyPost'],
                ['unit' => $sModule, 'action' => 'replyRemoved'],

                ['unit' => $sModule, 'action' => 'doVote'],
                ['unit' => $sModule, 'action' => 'undoVote'],

                ['unit' => $sModule . '_reactions', 'action' => 'doVote'],
                ['unit' => $sModule . '_reactions', 'action' => 'undoVote'],

                ['unit' => $sModule, 'action' => 'doVoteUp'],
                ['unit' => $sModule, 'action' => 'doVoteDown'],

                //--- Moderation related: For 'admins'.
                ['unit' => $sModule, 'action' => 'pending_approval'],
            ]
        ];

        if(!empty($this->_oConfig->CNF['FIELDS_DELAYED_PROCESSING'])) {
            $aResult['handlers'] = array_merge($aResult['handlers'], [
                ['group' => $sModule . '_object_publish_failed', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'publish_failed', 'module_name' => $sModule, 'module_method' => 'get_notifications_post_publish_failed', 'module_class' => 'Module', 'module_event_privacy' => ''],
                ['group' => $sModule . '_object_publish_succeeded', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'publish_succeeded', 'module_name' => $sModule, 'module_method' => 'get_notifications_post_publish_succeeded', 'module_class' => 'Module', 'module_event_privacy' => ''],
            ]);

            $aResult['settings'] = array_merge($aResult['settings'], [
                ['group' => 'content_updated', 'unit' => $sModule, 'action' => 'publish_failed', 'types' => ['personal']],
                ['group' => 'content_updated', 'unit' => $sModule, 'action' => 'publish_succeeded', 'types' => ['personal']],
            ]);

            $aResult['alerts'] = array_merge($aResult['alerts'], [
                ['unit' => $sModule, 'action' => 'publish_failed'],
                ['unit' => $sModule, 'action' => 'publish_succeeded'],
            ]);
        }

        return $aResult;
    }

    /**
     * Entry post for Notifications module
     */
    public function serviceGetNotificationsPost($aEvent)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]), '{bx_url_root}');
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'entry_privacy' => '', //may be empty or not specified. In this case Public privacy will be used.
            'lang_key' => '', //may be empty or not specified, or a string, or an array('site' => '...', 'email' => '...', 'push' => '...'). In this case of empty/not specified the default one from Notification module will be used.
            /*
             * Custom settings for email and/or push notifications can be provided here. 
             * Only necessary parts of 'settings' array can be used.
             * 
            'settings' => array(
                'email' => array(
                    'template' => '',   //--- custom email template
                    'markers' => '',    //--- markers to parse email parts (subject, body) with
                    'subject' => ''     //--- custom email subject
                ),
                'push' => array(
                    'subject' => ''     //--- custom push notification subject
                )
            )
             */
        );
    }

    public function serviceGetNotificationsPostPendingApproval($aEvent)
    {
        $aResult = $this->serviceGetNotificationsPost($aEvent);

        /**
         * Unset Entry Author because the notification is addressed to 'admins'.
         */
        if(isset($aResult['entry_author']))
            unset($aResult['entry_author']);

        return $aResult;
    }

    public function serviceGetNotificationsPostApproved($aEvent)
    {
        return $this->serviceGetNotificationsPost($aEvent);
    }

    public function serviceGetNotificationsPostPublishFailed($aEvent)
    {
        return $this->serviceGetNotificationsPost($aEvent);
    }

    public function serviceGetNotificationsPostPublishSucceeded($aEvent)
    {
        return $this->serviceGetNotificationsPost($aEvent);
    }


    /**
     * Entry post comment for Notifications module
     */
    public function serviceGetNotificationsComment($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $oComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
        if(!$oComment || !$oComment->isEnabled())
            return array();

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]), '{bx_url_root}');
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'subentry_sample' => $CNF['T']['txt_sample_comment_single'],
            'subentry_url' => bx_absolute_url($oComment->getViewUrl((int)$aEvent['subobject_id'], false), '{bx_url_root}'),
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

	/**
     * Entry post reply for Notifications module
     */
    public function serviceGetNotificationsReply($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$oComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], 0, false);
        if(!$oComment || !$oComment->isEnabled())
            return array();

    	$iParentId = (int)$aEvent['object_id'];
        $aParentInfo = $oComment->getQueryObject()->getCommentsBy(array('type' => 'id', 'id' => $iParentId));
        if(empty($aParentInfo) || !is_array($aParentInfo))
            return array();

        $iObjectId = (int)$aParentInfo['cmt_object_id'];
            $oComment->init($iObjectId);

        return array(
            'object_id' => $iObjectId,
            'entry_sample' => '_cmt_txt_sample_comment_single',
            'entry_url' => bx_absolute_url($oComment->getViewUrl($iParentId, false), '{bx_url_root}'),
            'entry_caption' => strmaxtextlen($aParentInfo['cmt_text'], 20, '...'),
            'entry_author' => (int)$aParentInfo['cmt_author_id'],
            'subentry_sample' => '_cmt_txt_sample_reply_to',
            'subentry_url' => bx_absolute_url($oComment->getViewUrl((int)$aEvent['subobject_id'], false), '{bx_url_root}'),
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

	/**
     * Entry post vote for Notifications module
     */
    public function serviceGetNotificationsVote($aEvent)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId);
        if(!$oVote || !$oVote->isEnabled())
            return array();

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]), '{bx_url_root}');
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'subentry_sample' => $CNF['T']['txt_sample_vote_single'],
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * Entry post vote for Notifications module
     */
    public function serviceGetNotificationsReaction($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $oReaction = BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], $iContentId);
        if(!$oReaction || !$oReaction->isEnabled())
            return array();

        $aSubentry = $oReaction->getTrackBy(array('type' => 'id', 'id' => (int)$aEvent['subobject_id']));
        if(empty($aSubentry) || !is_array($aSubentry))
            return array();

        $aSubentrySampleParams = array();
        $aReaction = $oReaction->getReaction($aSubentry['reaction']);
        if(!empty($aReaction['title']))
            $aSubentrySampleParams[] = $aReaction['title'];
        else
            $aSubentrySampleParams[] = '_undefined';

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]), '{bx_url_root}');
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'subentry_sample' => $CNF['T']['txt_sample_reaction_single'],
            'subentry_sample_params' => $aSubentrySampleParams,
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    /**
     * Entry post score -> vote up for Notifications module
     */
    public function serviceGetNotificationsScoreUp($aEvent)
    {
    	return $this->_serviceGetNotificationsScore('up', $aEvent);
    }

    /**
     * Entry post score -> vote up for Notifications module
     */
    public function serviceGetNotificationsScoreDown($aEvent)
    {
    	return $this->_serviceGetNotificationsScore('down', $aEvent);
    }

    protected function _serviceGetNotificationsScore($sType, $aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $oScore = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $iContentId);
        if(!$oScore || !$oScore->isEnabled())
            return array();

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]), '{bx_url_root}');
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'subentry_sample' => $CNF['T']['txt_sample_score_' . $sType . '_single'],
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }
    
    /**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
    	$sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_timeline_post', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted')
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'added'),
                array('unit' => $sModule, 'action' => 'edited'),
                array('unit' => $sModule, 'action' => 'deleted'),
            )
        );
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        if((!empty($CNF['FIELD_STATUS']) && $aContentInfo[$CNF['FIELD_STATUS']] != 'active') || (!empty($CNF['FIELD_STATUS_ADMIN']) && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] != 'active'))
            return false;

        $iUserId = $this->getUserId();
        $iAuthorId = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];
        $iAuthorIdAbs = abs($iAuthorId);
        if($iAuthorId < 0 && ((is_numeric($aEvent['owner_id']) && $iAuthorIdAbs == (int)$aEvent['owner_id']) || (is_array($aEvent['owner_id']) && in_array($iAuthorIdAbs, $aEvent['owner_id']))) && $iAuthorIdAbs != $iUserId)
            return false;

        //--- Views
        $oViews = isset($CNF['OBJECT_VIEWS']) ? BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aViews = array();
        if ($oViews && $oViews->isEnabled())
            $aViews = array(
                'system' => $CNF['OBJECT_VIEWS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['views']
            );

        //--- Votes
        $oVotes = isset($CNF['OBJECT_VOTES']) ? BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aVotes = array();
        if ($oVotes && $oVotes->isEnabled())
            $aVotes = array(
                'system' => $CNF['OBJECT_VOTES'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['votes']
            );
        
        //--- Reactions
        $oReactions = isset($CNF['OBJECT_REACTIONS']) ? BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aReactions = array();
        if ($oReactions && $oReactions->isEnabled())
            $aReactions = array(
                'system' => $CNF['OBJECT_REACTIONS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['rvotes']
            );

        //--- Scores
        $oScores = isset($CNF['OBJECT_SCORES']) ? BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aScores = array();
        if ($oScores && $oScores->isEnabled())
            $aScores = array(
                'system' => $CNF['OBJECT_SCORES'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'score' => $aContentInfo['score']
            );

        //--- Reports
        $oReports = isset($CNF['OBJECT_REPORTS']) ? BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aReports = array();
        if ($oReports && $oReports->isEnabled())
            $aReports = array(
                'system' => $CNF['OBJECT_REPORTS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['reports']
            );

        //--- Comments
        $oCmts = isset($CNF['OBJECT_COMMENTS']) ? BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $aContentInfo[$CNF['FIELD_ID']]) : null;

        $aComments = array();
        if($oCmts && $oCmts->isEnabled())
            $aComments = array(
                'system' => $CNF['OBJECT_COMMENTS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['comments']
            );

        //--- Title & Description
        $sTitle = !empty($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : '';
        if(empty($sTitle) && !empty($aContentInfo[$CNF['FIELD_TEXT']]))
            $sTitle = $aContentInfo[$CNF['FIELD_TEXT']];

        $iOwnerId = $iAuthorIdAbs;
        if(isset($aEvent['object_privacy_view']) && (int)$aEvent['object_privacy_view'] < 0)
            $iOwnerId = abs($aEvent['object_privacy_view']);

        $bCache = true;
        $aContent = $this->_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);
        if(isset($aContent['_cache'])) {
            $bCache = (bool)$aContent['_cache'];

            unset($aContent['_cache']);
        }

        return array(
            '_cache' => $bCache,
            'owner_id' => $iOwnerId,
            'object_owner_id' => $iAuthorId,
            'icon' => !empty($CNF['ICON']) ? $CNF['ICON'] : '',
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
            'sample_action' => isset($CNF['T']['txt_sample_action']) ? $CNF['T']['txt_sample_action'] : '',
            'url' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']])),
            'content' => $aContent, //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'views' => $aViews,
            'votes' => $aVotes,
            'reactions' => $aReactions,
            'scores' => $aScores,
            'reports' => $aReports,
            'comments' => $aComments,
            'title' => $sTitle, //may be empty.
            'description' => '' //may be empty.
        );
    }

    public function serviceGetTimelinePostAllowedView($aEvent)
    {
        $iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return _t('_sys_txt_access_denied');

        return $this->serviceCheckAllowedViewForProfile($aContentInfo);
    }

    /**
     * Check particular action permission without content
     * @param $sAction action to check, for example: Browse, Add
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckAllowed($sAction, $isPerformAction = false)
    {
        $sMethod = 'checkAllowed' . bx_gen_method_name($sAction);
        if (!method_exists($this, $sMethod))
            return _t('_sys_request_method_not_found_cpt');

        return $this->$sMethod($isPerformAction);
    }
    
    /**
     * Check particular action permission with content
     * @param $sAction action to check, for example: View, Edit
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckAllowedWithContent($sAction, $iContentId, $isPerformAction = false)
    {
        if (!$iContentId || !($aContentInfo = $this->_oDb->getContentInfoById($iContentId)))
            return _t('_sys_request_page_not_found_cpt');

        $sMethod = 'checkAllowed' . bx_gen_method_name($sAction);
        if (!method_exists($this, $sMethod))
            return _t('_sys_request_method_not_found_cpt');

        return $this->$sMethod($aContentInfo, $isPerformAction);
    }

    /**
     * Check particular action permission with content for specified profile
     * @param $sAction action to check, for example: View, Edit
     * @param $iContentId content ID
     * @param $iProfileId profile ID which the permissions to be cheked for
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckAllowedWithContentForProfile($sAction, $iContentId, $iProfileId, $isPerformAction = false)
    {
        if (!$iContentId || !($aContentInfo = $this->_oDb->getContentInfoById($iContentId)))
            return _t('_sys_request_page_not_found_cpt');

        $sMethod = 'checkAllowed' . bx_gen_method_name($sAction) . 'ForProfile';
        if (!method_exists($this, $sMethod))
            return _t('_sys_request_method_not_found_cpt');

        return $this->$sMethod($aContentInfo, $iProfileId, $isPerformAction);
    }
    
    public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        //negative id used in comments for reports
        if ($iContentId < 0)
            return CHECK_ACTION_RESULT_ALLOWED;
        
        return $this->serviceCheckAllowedWithContent('comments_view', $iContentId);
    }
    
    public function serviceCheckAllowedCommentsPost($iContentId, $sObjectComments) 
    {
        //negative id used in comments for reports
        if ($iContentId < 0)
            return CHECK_ACTION_RESULT_ALLOWED;
        
        return $this->serviceCheckAllowedWithContent('comments_post', $iContentId);
    }

    public function serviceGetContentOwnerProfileId ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        // file owner must be author of the content or profile itself in case of profile based module
        if ($iContentId) {
            $sModule = $this->getName();
            if ($this instanceof iBxDolProfileService && BxDolService::call($sModule, 'act_as_profile')) {
                $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $sModule);
            }
            else {
                $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
                $oProfile = $aContentInfo ? BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_AUTHOR']]) : null;
            }
            $iProfileId = $oProfile ? $oProfile->id() : bx_get_logged_profile_id();
        }
        else {
            $iProfileId = bx_get_logged_profile_id();
        }

        return $iProfileId;
    }
	
    public function serviceGetBadges($iContentId,  $bIsSingle = false, $bIsCompact  = false)
    {
        $sModule = $this->getName();

        $oBadges = BxDolBadges::getInstance();
        $aBadges = $oBadges->getData(array(
            'type' => ($bIsSingle ? 'by_module&object2_single' : 'by_module&object2'),
            'module' => $sModule,
            'object_id' => $iContentId
        ));

        $sResult = false;
        bx_alert('system', 'get_badges', 0, 0, array(
            'module' => $sModule, 
            'content_id' => $iContentId,
            'is_single' => $bIsSingle,
            'is_compact' => $bIsCompact,
            'badges' => $aBadges, 
            'override_result' => &$sResult
        ));
        if($sResult !== false)
            return $sResult;

        if(empty($aBadges) || !is_array($aBadges))
            return '';

        if($bIsSingle)
            return BxDolService::call('system', 'get_badge', array($aBadges[0], $bIsCompact), 'TemplServices');

        $aBadgesOutput = array();
        foreach($aBadges as $aBadge)
            $aBadgesOutput[] =  array('badge' => BxDolService::call('system', 'get_badge', array($aBadge, $bIsCompact), 'TemplServices'));

        return $this->_oTemplate->parseHtmlByName('badges.html', array(
            'bx_repeat:items' => $aBadgesOutput,
        ));
    }
    
    /**
     * @page service Service Calls
     * @section bx_base_general Base General
     * @subsection bx_base_general-page_blocks Page Blocks
     * @subsubsection bx_base_general-categories_multi_list categories_multi_list
     * 
     * @code bx_srv('bx_posts', 'categories_multi_list', [...]); @endcode
     * 
     * Display multi-categorories block with number of posts in each category
     * @param $bDisplayEmptyCats display empty categories
     * 
     * @see BxBaseModGeneralModule::serviceCategoriesMultiList
     */
    /** 
     * @ref bx_base_general-categories_multi_list "categories_multi_list"
     */
    public function serviceCategoriesMultiList($bDisplayEmptyCats = true)
    {
        $aContextInfo = bx_get_page_info();

        $mProfileContextId = false;
        if ($aContextInfo !== false)
            $mProfileContextId = $aContextInfo['context_profile_id'];
        
        $oCategories = BxDolCategories::getInstance();
        if ($mProfileContextId)
            $aCats = $oCategories->getData([
                'type' => 'by_module&context_with_num', 
                'module' => $this->getName(), 
                'context_id' => $mProfileContextId
            ]);
        else{
            $aCats = $oCategories->getData([
                'type' => 'by_module_with_num', 
                'module' => $this->getName()
            ]);
        }
        $aVars = array('bx_repeat:cats' => array());
        foreach ($aCats as $oCat) {
            $sValue = $oCat['value'];
            $iNum = $oCat['num'];
            
            $aVars['bx_repeat:cats'][] = array(
                'url' => $oCategories->getUrl($this->getName(), $sValue, $mProfileContextId ? '&context_id=' . $mProfileContextId : ''),
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
     * ======
     * PERMISSION METHODS
     * ======
     */
    public function serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction = false, $iProfileId = false)
    {
        $mixedResult = $this->_serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId);

        // check alert to allow custom checks        
        bx_alert('system', 'check_allowed_view', 0, 0, array('module' => $this->getName(), 'content_info' => $aDataEntry, 'profile_id' => $iProfileId, 'override_result' => &$mixedResult));

        return $mixedResult;
    }

    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedBrowse ()
    {
        // check alert to allow custom checks
        $mixedResult = null;
        bx_alert('system', 'check_allowed_browse', 0, 0, array('module' => $this->getName(), 'profile_id' => $this->_iProfileId, 'override_result' => &$mixedResult));
        if($mixedResult !== null)
            return $mixedResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        return $this->serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedViewForProfile ($aDataEntry, $iProfileId, $isPerformAction = false)
    {
        return $this->serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedAdd ($isPerformAction = false)
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'create entry', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedApprove ($aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;
        $sError = '_sys_txt_access_denied';

        if($this->_oConfig->isAutoApproveEnabled())
            return _t($sError);

        if($aDataEntry[$CNF['FIELD_STATUS_ADMIN']] != BX_BASE_MOD_GENERAL_STATUS_PENDING)
            return _t($sError);

        // moderator always have access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check for context's admins 
        if (!empty($CNF['FIELD_ALLOW_VIEW_TO']) && (int)$aDataEntry[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $oProfile = BxDolProfile::getInstance(-(int)$aDataEntry[$CNF['FIELD_ALLOW_VIEW_TO']]);
            if ($oProfile){
                $sModule = $oProfile->getModule();
                $aEntity = BxDolRequest::serviceExists($sModule, 'get_all') ? BxDolService::call($sModule, 'get_all', array(array('type' => 'id', 'id' => $oProfile->getContentId()))) : array();

                $oModule = BxDolModule::getInstance($sModule);

                // check for context's extra roles with rights
                if (method_exists($oModule, 'isAllowedModuleActionByProfile')) {
                    $bResult = $oModule->isAllowedModuleActionByProfile($oProfile->getContentId(), $this->getName(), 'edit_any');
                    if ($bResult !== NULL) return $bResult;
                }

                // if allowed edit a group then allowed to edit anything inside its context
                if(isset($aEntity) && $oModule->checkAllowedEdit($aEntity) === CHECK_ACTION_RESULT_ALLOWED){
                    return CHECK_ACTION_RESULT_ALLOWED;
                }
            }
        }

        return _t($sError);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        // moderator and owner always have access
        if ($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId || -$aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check for context's admins 
        if (!empty($this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']) && (int)$aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $oProfile = BxDolProfile::getInstance(-(int)$aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]);
            if ($oProfile){
                $sModule = $oProfile->getModule();
                $aEntity = BxDolRequest::serviceExists($sModule, 'get_all') ? BxDolService::call($sModule, 'get_all', array(array('type' => 'id', 'id' => $oProfile->getContentId()))) : array();

                $oModule = BxDolModule::getInstance($sModule);

                // check for context's extra roles with rights
                if (method_exists($oModule, 'isAllowedModuleActionByProfile')) {
                    $bResult = $oModule->isAllowedModuleActionByProfile($oProfile->getContentId(), $this->getName(), 'edit_any');
                    if ($bResult !== NULL) return $bResult;
                }

                // if allowed edit a group then allowed to edit anything inside its context
                if(isset($aEntity) && $oModule->checkAllowedEdit($aEntity) === CHECK_ACTION_RESULT_ALLOWED){
                    return CHECK_ACTION_RESULT_ALLOWED;
                }
            }
        }

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isAdministrator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'delete entry', $this->getName(), $isPerformAction);
        if (($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId || -$aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId) && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        // check for context's admins 
        if (isset($this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']) && (int)$aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']] < 0){
            $oProfile = BxDolProfile::getInstance(-(int)$aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]);
            if ($oProfile){
                $sModule = $oProfile->getModule();
                $aEntity = BxDolRequest::serviceExists($sModule, 'get_all') ? BxDolService::call($sModule, 'get_all', array(array('type' => 'id', 'id' => $oProfile->getContentId()))) : array();

                $oModule = BxDolModule::getInstance($sModule);

                // check for context's extra roles with rights
                if (method_exists($oModule, 'isAllowedModuleActionByProfile')) {
                    $bResult = $oModule->isAllowedModuleActionByProfile($oProfile->getContentId(), $this->getName(), 'delete_any');
                    if ($bResult !== NULL) return $bResult;
                }

                // if allowed delete a group then allowed to delete anything inside its context
                if(isset($aEntity) && $oModule->checkAllowedDelete($aEntity) === CHECK_ACTION_RESULT_ALLOWED){
                    return CHECK_ACTION_RESULT_ALLOWED;
                }
            }
        }

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetMembership (&$aDataEntry, $isPerformAction = false)
    {
        // admin always has access
        if (isAdmin())
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set acl level', 'system', $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEditAnyEntry ($isPerformAction = false)
    {
    	return $this->checkAllowedEditAnyEntryForProfile($isPerformAction, $this->_iProfileId);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDeleteAnyEntry ($isPerformAction = false)
    {
    	return $this->checkAllowedDeleteAnyEntryForProfile($isPerformAction, $this->_iProfileId);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEditAnyEntryForProfile ($isPerformAction = false, $iProfileId = false)
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

    	$aCheck = checkActionModule($iProfileId, MEMBERSHIP_ACTION_EDIT_ANY_ENTRY, $this->getName(), $isPerformAction);
    	if($aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
    		return CHECK_ACTION_RESULT_ALLOWED;

    	return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDeleteAnyEntryForProfile ($isPerformAction = false, $iProfileId = false)
    {
        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

    	$aCheck = checkActionModule($iProfileId, MEMBERSHIP_ACTION_DELETE_ANY_ENTRY, $this->getName(), $isPerformAction);
    	if($aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
    		return CHECK_ACTION_RESULT_ALLOWED;

    	return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedCommentsView ($aContentInfo, $isPerformAction = false)
    {
        return $this->checkAllowedView ($aContentInfo, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedCommentsPost ($aContentInfo, $isPerformAction = false)
    {
        return $this->checkAllowedView ($aContentInfo, $isPerformAction);
    }

    protected function _serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        if(empty($aDataEntry) || !is_array($aDataEntry))
            return _t('_sys_txt_not_found');

        // moderator and owner always have access
        if(!empty($iProfileId) && (abs($aDataEntry[$CNF['FIELD_AUTHOR']]) == (int)$iProfileId || $this->_isModeratorForProfile($isPerformAction, $iProfileId)))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($iProfileId, 'view entry', $this->getName(), $isPerformAction);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        // check privacy
        if(!empty($CNF['OBJECT_PRIVACY_VIEW'])) {
            $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
            if ($oPrivacy && !$oPrivacy->check($aDataEntry[$CNF['FIELD_ID']], $iProfileId))
                return _t('_sys_access_denied_to_private_content');
        }

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function _serviceBrowse ($sMode, $aParams = false, $iDesignBox = BX_DB_PADDING_DEF, $bDisplayEmptyMsg = false, $bAjaxPaginate = true, $sClassSearchResult = 'SearchResult')
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse()))
            return bx_is_api() ? [['id' => 1, 'type' => 'msg', 'data' => $sMsg]] : MsgBox($sMsg);

        bx_import($sClassSearchResult, $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . $sClassSearchResult;
        $o = new $sClass($sMode, $aParams);

        $o->setDesignBoxTemplateId($iDesignBox);
        $o->setDisplayEmptyMsg($bDisplayEmptyMsg);
        $o->setAjaxPaginate($bAjaxPaginate);
        $o->setUnitParams(array('context' => $sMode));
        if (isset($aParams['condition']) && is_array($aParams['condition']))
            $o->setCustomCurrentCondition($aParams['condition']);

        if ($o->isError)
            return '';

        if ($s = $o->processing())
            return bx_is_api() ? [['id' => 1, 'type' => 'browse', 'data' => $s]] : $s;
        else
            return '';
    }

    /**
     * Shows a list of profiles.
     */
    protected function _serviceBrowseQuick($aProfiles, $iStart = 0, $iLimit = 4, $aAdditionalParams = array())
    {
        // get paginate object
        $oPaginate = new BxTemplPaginate(array(
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}', " . bx_js_string(json_encode($aAdditionalParams)) . ");",
            'num' => count($aProfiles),
            'per_page' => $iLimit,
            'start' => $iStart,
        ));

        // remove last item from connection array, because we've got one more item for pagination calculations only
        if (count($aProfiles) > $iLimit)
            array_pop($aProfiles);

        // get profiles HTML
        $s = '';
        foreach ($aProfiles as $mixedProfile) {
            $bProfile = is_array($mixedProfile);

            $oProfile = BxDolProfile::getInstance($bProfile ? (int)$mixedProfile['id'] : (int)$mixedProfile);
            if(!$oProfile)
                continue;

            $aUnitParams = array('template' => array('name' => 'unit', 'size' => 'thumb'));
            if(BxDolModule::getInstance($oProfile->getModule()) instanceof BxBaseModGroupsModule)
                $aUnitParams['template']['name'] = 'unit_wo_cover';

            if($bProfile && is_array($mixedProfile['info']))
                $aUnitParams['template']['vars'] = $mixedProfile['info'];

            $s .= $oProfile->getUnit(0, $aUnitParams);
        }

        $aTmplVarsPaginate = [];
        if($iStart || $oPaginate->getNum() > $iLimit)
            $aTmplVarsPaginate = ['paginate' => $oPaginate->getSimplePaginate()];

        return $this->_oTemplate->parseHtmlByName('browse_quick.html', array(
            'code' => $s,
            'bx_if:show_paginate' => [
                'condition' => !empty($aTmplVarsPaginate),
                'content' => $aTmplVarsPaginate
            ]
        ));
    }

    // ====== COMMON METHODS
    public function onPublished($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return;

        $aParams = $this->_alertParams($aContentInfo);
        bx_alert($this->getName(), 'added', $iContentId, $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }

    public function onFailed($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return;

        $aParams = array('object_author_id' => $aContentInfo[$CNF['FIELD_AUTHOR']]);
        bx_alert($this->getName(), 'failed', $iContentId, $aContentInfo[$CNF['FIELD_AUTHOR']], $aParams);
    }

    public function onApprove($mixedContent)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!is_array($mixedContent))
            $mixedContent = $this->_oDb->getContentInfoById((int)$mixedContent);

        $this->alertAfterApprove($mixedContent);

        $this->onPublished($mixedContent[$CNF['FIELD_ID']]);
    }

    public function alertAfterAdd($aContentInfo) {}
    
    public function alertAfterEdit($aContentInfo) {}

    public function alertAfterApprove($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $iId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $sModule = $this->getName();
        $aParams = $this->_alertParams($aContentInfo);
        bx_alert($sModule, 'approved', $iId, false, $aParams);
    }

    /**
     * Get array of params to be passed in Add/Edit Alert.
     */
    protected function _alertParams($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        return [
            'source' => $this->_oConfig->getName() . '_' . (int)$aContentInfo[$CNF['FIELD_ID']],

            'object_author_id' => (int)$aContentInfo[$CNF['FIELD_AUTHOR']]
        ];
    }

    public function processMetasAdd($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_METATAGS'])) 
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        $bFldStatus = !empty($CNF['FIELD_STATUS']);
        $bFldStatusAdmin = !empty($CNF['FIELD_STATUS_ADMIN']);
        $bContentInfo = $aContentInfo && (!$bFldStatus || ($bFldStatus && $aContentInfo[$CNF['FIELD_STATUS']] == 'active')) && (!$bFldStatusAdmin || ($bFldStatusAdmin && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] == 'active'));
        if(!$bContentInfo)
            return false;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $oMetatags->metaAddAuto($iContentId, $aContentInfo, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);

        $sKey = 'FIELD_LOCATION';
        if($oMetatags->locationsIsEnabled() && !empty($CNF[$sKey]) && !empty($aContentInfo[$CNF[$sKey]])) {
            $aLocation = unserialize($aContentInfo[$CNF[$sKey]]);
            if(!empty($aLocation) && is_array($aLocation))
                call_user_func_array(array($oMetatags, 'locationsAdd'), array_merge(array($iContentId), array_values($aLocation)));
        }

        $sKey = 'FIELD_LABELS';
        if($oMetatags->keywordsIsEnabled() && !empty($CNF[$sKey]) && !empty($aContentInfo[$CNF[$sKey]])) {
            $aLabels = unserialize($aContentInfo[$CNF[$sKey]]);
            if(!empty($aLabels) && is_array($aLabels))
                foreach ($aLabels as $sLabel) {
                    if(!preg_match("/(\pL[\pL\pN_]+)/u", $sLabel)) 
                        continue;

                    $oMetatags->keywordsAddOne($iContentId, $sLabel, false);
                }
        }
        
        return true;
    }

    public function processMetasEdit($iContentId, $oForm)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_METATAGS']))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        $bFldStatus = isset($CNF['FIELD_STATUS']);
        $bFldStatusAdmin = isset($CNF['FIELD_STATUS_ADMIN']);
        $bContentInfo = $aContentInfo && (!$bFldStatus || ($bFldStatus && $aContentInfo[$CNF['FIELD_STATUS']] == 'active')) && (!$bFldStatusAdmin || ($bFldStatusAdmin && $aContentInfo[$CNF['FIELD_STATUS_ADMIN']] == 'active'));
        if(!$bContentInfo)
            return false;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $oMetatags->metaAddAuto($iContentId, $aContentInfo, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT']);

        $sKey = 'FIELD_LOCATION';
        if($oMetatags->locationsIsEnabled() && !empty($CNF[$sKey]) && !empty($aContentInfo[$CNF[$sKey]])) {
            $aLocation = unserialize($aContentInfo[$CNF[$sKey]]);
            if(!empty($aLocation) && is_array($aLocation))
                call_user_func_array(array($oMetatags, 'locationsAdd'), array_merge(array($iContentId), array_values($aLocation)));
        }

        $sKey = 'FIELD_LABELS';
        if($oMetatags->keywordsIsEnabled() && !empty($CNF[$sKey]) && !empty($aContentInfo[$CNF[$sKey]])) {
            $aLabels = unserialize($aContentInfo[$CNF[$sKey]]);
            if(!empty($aLabels) && is_array($aLabels))
                foreach ($aLabels as $sLabel) {
                    if(!preg_match("/(\pL[\pL\pN_]+)/u", $sLabel))
                        continue;

                    $oMetatags->keywordsAddOne($iContentId, $sLabel, false);
                }
        }

        return true;
    }

    public function getEntryImageData($aContentInfo, $sField = 'FIELD_THUMB', $aTranscoders = array())
    {
        if(empty($aTranscoders))
            $aTranscoders = array('OBJECT_TRANSCODER_COVER', 'OBJECT_IMAGES_TRANSCODER_COVER', 'OBJECT_IMAGES_TRANSCODER_GALLERY');

        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF[$sField]) || empty($aContentInfo[$CNF[$sField]]) || empty($CNF['OBJECT_STORAGE']))
            return false;

        $iId = (int)$aContentInfo[$CNF[$sField]];
        $aFile = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE'])->getFile($iId);
        if(!empty($aFile) && is_array($aFile) && strpos($aFile['mime_type'], 'svg') !== false)
            return array('id' => $iId, 'object' => $CNF['OBJECT_STORAGE']);

        foreach($aTranscoders as $sTranscoder)
            if(!empty($CNF[$sTranscoder]))
                return array('id' => $iId, 'transcoder' => $CNF[$sTranscoder]);

        return array('id' => $iId, 'object' => $CNF['OBJECT_STORAGE']);
    }

    public function getEntryAllActions ($mixedContent = false, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        if(!empty($mixedContent)) {
            if(!is_array($mixedContent))
                $mixedContent = array((int)$mixedContent, (method_exists($this->_oDb, 'getContentInfoById')) ? $this->_oDb->getContentInfoById((int)$mixedContent) : array());
        }
        else {
            $mixedContent = $this->_getContent();
            if($mixedContent === false)
                return false;
        }

        list($iContentId, $aContentInfo) = $mixedContent;

        $sObjectMenu = !empty($aParams['object_menu']) ? $aParams['object_menu'] : '';
        if(empty($sObjectMenu) && !empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL']))
            $sObjectMenu = $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL'];

        if(empty($sObjectMenu))
            return false;

        $sEntryTitle = !empty($aParams['entry_title']) ? $aParams['entry_title'] : '';
        if(empty($sEntryTitle) && !empty($CNF['FIELD_TITLE']) && !empty($aContentInfo[$CNF['FIELD_TITLE']]))
            $sEntryTitle = $aContentInfo[$CNF['FIELD_TITLE']];

        $sEntryUrl = !empty($aParams['entry_url']) ? $aParams['entry_url'] : '';
        if(empty($sEntryUrl) && !empty($CNF['URI_VIEW_ENTRY']))
            $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId));

        $iEntryThumb = !empty($aParams['entry_thumb']) ? (int)$aParams['entry_thumb'] : 0;
        if(empty($iEntryThumb) && !empty($CNF['FIELD_THUMB']) && !empty($aContentInfo[$CNF['FIELD_THUMB']]))
            $iEntryThumb = (int)$aContentInfo[$CNF['FIELD_THUMB']];

        $sObjectStorage = !empty($aParams['object_storage']) ? $aParams['object_storage'] : false;
        if(empty($sObjectStorage) && !empty($CNF['OBJECT_STORAGE']))
            $sObjectStorage = $CNF['OBJECT_STORAGE'];

        $sObjectTranscoder = !empty($aParams['object_transcoder']) ? $aParams['object_transcoder'] : false;

        $aMarkers = [
            'id' => $iContentId,
            'module' => $this->_oConfig->getName(),
            'title' => !empty($sEntryTitle) ? $sEntryTitle : '',
            'url' => !empty($sEntryUrl) ? $sEntryUrl : '',
            'img_url' => ''
        ];

        if(!empty($iEntryThumb)) {
            if(!empty($sObjectTranscoder))
                $o = BxDolTranscoder::getObjectInstance($sObjectTranscoder);
            else if(!empty($sObjectStorage))
                $o = BxDolStorage::getObjectInstance($sObjectStorage);

            $sImageUrl = $o ? $o->getFileUrlById($iEntryThumb) : '';
            if(!empty($sImageUrl))
                $aMarkers['img_url'] = $sImageUrl;
        }

        $oActions = BxDolMenu::getObjectInstance($sObjectMenu, $this->_oTemplate);
        if(!$oActions)
            return false;

        $oActions->setContentId($iContentId);
        $oActions->addMarkers($aMarkers);

        return $oActions->{bx_is_api() ? 'getCodeAPI' : 'getCode'}();
    }

    public function getProfileId()
    {
    	return bx_get_logged_profile_id();
    }

    public function getProfileInfo($iUserId = false)
    {
        $oProfile = $this->getObjectUser($iUserId);

        $oAccount = null;
        if($oProfile && !($oProfile instanceof BxDolProfileUndefined) && !($oProfile instanceof BxDolProfileAnonymous))
            $oAccount = $oProfile->getAccountObject();
        $bAccount = !empty($oAccount);

        if(!$bAccount)
            $oProfile = BxDolProfileUndefined::getInstance();

        return array(
            'id' => $oProfile->id(),
            'name' => $oProfile->getDisplayName(),
            'email' => $bAccount ? $oAccount->getEmail() : '',
            'link' => $oProfile->getUrl(),
            'icon' => $oProfile->getIcon(),
            'thumb' => $oProfile->getThumb(),
            'avatar' => $oProfile->getAvatar(),
            'unit' => $oProfile->getUnit(),
            'active' => $oProfile->isActive(),
        );
    }

    public function getObjectUser($iUserId = false)
    {
    	bx_import('BxDolProfile');
        return BxDolProfile::getInstanceMagic($iUserId);
    }

    public function getObjectFavorite($sSystem = '', $iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($sSystem) && !empty($CNF['OBJECT_FAVORITES']))
            $sSystem = $CNF['OBJECT_FAVORITES'];

        if(empty($sSystem))
            return false;

        $oFavorite = BxDolFavorite::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oFavorite || !$oFavorite->isEnabled())
            return false;

        return $oFavorite;
    }

	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getUserIp()
    {
        return getVisitorIP();
    }
    
    public function getUserInfo($iUserId = 0)
    {
        $oProfile = BxDolProfile::getInstanceMagic($iUserId);

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit(),
            $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
        );
    }
    
    public function getUserInfoWithBadges($iUserId = 0)
    {
        $oProfile = BxDolProfile::getInstanceMagic($iUserId);

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit(),
            $oProfile->getUnit(0, array('template' => 'unit_wo_info')),
            $oProfile->getBadges()
        );
    }

    public function isMenuItemVisible($sObject, &$aItem, &$aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        // default visible settings
        if(!BxDolAcl::getInstance()->isMemberLevelInSet($aItem['visible_for_levels']))
            return false;

        if (!empty($aItem['visibility_custom'])) {
            $oMenu = BxDolMenu::getObjectInstance($sObject);
            if ($oMenu && !BxDolService::callSerialized($aItem['visibility_custom'], $oMenu->getMarkers()))
                return false;
        }
        
        // get custom function name to check menu item visibility
        $sFuncCheckAccess = false;
        if(isset($CNF['MENU_ITEM_TO_METHOD'][$sObject][$aItem['name']]))
            $sFuncCheckAccess = $CNF['MENU_ITEM_TO_METHOD'][$sObject][$aItem['name']];

        // check custom visibility settings defined in module config class
        if(!isset($aContentInfo))
            $aContentInfo = array();

        if($sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED !== call_user_func_array(array($this, $sFuncCheckAccess), array(&$aContentInfo)))
            return false;

        return true;
    }

    public function _isModerator ($isPerformAction = false)
    {
        return $this->_isModeratorForProfile($isPerformAction, $this->_iProfileId);
    }

    public function _isModeratorForProfile($isPerformAction = false, $iProfileId = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedEditAnyEntryForProfile ($isPerformAction, $iProfileId);
    }

    public function _isAdministrator ($isPerformAction = false)
    {
        return $this->_isAdministratorForProfile($isPerformAction, $this->_iProfileId);
    }

    public function _isAdministratorForProfile($isPerformAction = false, $iProfileId = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedDeleteAnyEntryForProfile ($isPerformAction, $iProfileId);
    }

    public function _prepareAuditParams($aContentInfo, $bIsSaveData = true, $aOverrideAuditParams  = array())
    {
        $CNF = &$this->_oConfig->CNF;
        
        $iContextId = isset($CNF['FIELD_ALLOW_VIEW_TO']) && (!empty($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) ? - $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] : 0;
        
        $AuditParams = array(
            'content_title' => (isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']])) ? $aContentInfo[$CNF['FIELD_TITLE']] : '',
            'context_profile_id' => $iContextId,
            'content_info_object' =>  isset($CNF['OBJECT_CONTENT_INFO']) ? $CNF['OBJECT_CONTENT_INFO'] : '',
            'data' => ($bIsSaveData ? $aContentInfo : array())
        );
        if ($iContextId > 0)
            $AuditParams['context_profile_title'] = BxDolProfile::getInstance($iContextId)->getDisplayName();
        
        $AuditParams = array_merge($AuditParams, $aOverrideAuditParams);
        
        return $AuditParams;
    }
    
    public function _getFavoriteListUrl ($iListId, $iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_FAVORITES_LIST'] . '&profile_id=' . $iProfileId . '&list_id=' . $iListId));
    }
    
    
    // ====== PROTECTED METHODS

    protected function _serviceEntityForm ($sFormMethod, $iContentId = 0, $sDisplay = false, $sCheckFunction = false, $bErrorMsg = true)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFormMethod((int)$iContentId, $sDisplay, $sCheckFunction, $bErrorMsg);
    }

    protected function _serviceTemplateFunc ($sFunc, $iContentId, $sFuncGetContent = 'getContentInfoById')
    {
        return $this->_serviceTemplateFuncEx($sFunc, $iContentId, array('function_get_content' => $sFuncGetContent));
    }
    
    protected function _serviceTemplateFuncEx ($sFunc, $iContentId, $aParams = array())
    {
        if (!isset($aParams['function_get_content']))
            $aParams['function_get_content'] = 'getContentInfoById';
        
        $mixedContent = $this->_getContent($iContentId, $aParams['function_get_content']);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        unset($aParams['function_get_content']);
        
        if (!empty($aParams))
            return $this->_oTemplate->$sFunc($aContentInfo, $aParams);
        else
            return $this->_oTemplate->$sFunc($aContentInfo);
    }

    protected function _getApproveForm($iContentId, $aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $sForm = 'sys_manage';
        if(!empty($CNF['OBJECT_FORM_MANAGE']))
            $sForm = $CNF['OBJECT_FORM_MANAGE'];

        $sFormDisplay = 'sys_manage_approve';
        if(!empty($CNF['OBJECT_FORM_MANAGE_APPROVE']))
            $sFormDisplay = $CNF['OBJECT_FORM_MANAGE_APPROVE'];

        $oForm = BxTemplFormView::getObjectInstance($sForm, $sFormDisplay);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'approve';
        $oForm->aInputs['content_id']['value'] = $iContentId;
        foreach($oForm->aInputs['controls'] as $iKey => $mixedValue)
            if(is_numeric($iKey) && isset($mixedValue['name']) && $mixedValue['name'] == 'do_submit')
                $oForm->aInputs['controls'][$iKey]['value'] = _t('_sys_form_manage_input_do_submit_approve');

        bx_alert($this->_oConfig->getName(), 'get_approve_form', 0, 0, array(
            'content_id' => $iContentId,
            'content_info' => $aContentInfo,
            'override_result' => &$oForm
        ));

        return $oForm;
    }

    protected function _sendApproveMessage($iContentId, $aContentInfo, &$oForm)
    {
        $CNF = &$this->_oConfig->CNF;

        $sETemplate = 't_ManageApprove';
        if(!empty($CNF['ETEMPLATE_MANAGE_APPROVE']))
            $sETemplate = $CNF['ETEMPLATE_MANAGE_APPROVE'];

        $aEParams = array(
            'content_title' => !empty($CNF['FIELD_TITLE']) && !empty($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : '',
            'notes' => $oForm->getCleanValue('notes'),
        );

        bx_alert($this->_oConfig->getName(), 'get_approve_message', 0, 0, array(
            'content_id' => $iContentId,
            'content_info' => $aContentInfo,
            'form' => $oForm,
            'email_template' => &$sETemplate,
            'email_params' => &$aEParams
        ));

        return sendMailTemplate($sETemplate, 0, $aContentInfo[$CNF['FIELD_AUTHOR']], $aEParams);
    }

    protected function _processModerationNotifications($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['PARAM_AUTO_APPROVE']) || getParam($CNF['PARAM_AUTO_APPROVE']) == 'on')
            return;

        $aRecipients = [];
        if(getParam('sys_notify_to_approve_by_role') == 'on' && bx_srv('system', 'is_module_content', [$this->_oConfig->getName()]) && !empty($CNF['FIELD_ALLOW_VIEW_TO']) && (int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] < 0) {
            $iContextProfileId = abs((int)$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);
            $oContextProfile = BxDolProfile::getInstance($iContextProfileId);

            $aRecipients = bx_srv($oContextProfile->getModule(), 'get_admins_to_manage_content', [$iContextProfileId]);
        }

        $sModule = $this->getName();

        if(empty($aRecipients))
            $aRecipients = BxDolAclQuery::getInstance()->getProfilesByAction([
                MEMBERSHIP_ACTION_EDIT_ANY_ENTRY, 
                MEMBERSHIP_ACTION_DELETE_ANY_ENTRY
            ], [
                'module' => $sModule, 
                'ids_only' => true
            ]);

        if(empty($aRecipients))
            return;

        foreach($aRecipients as $iRecipientId)
            bx_alert($sModule, 'pending_approval', $aContentInfo[$CNF['FIELD_ID']], $aContentInfo[$CNF['FIELD_AUTHOR']], [
                'object_author_id' => (int)$iRecipientId
            ]);
    }

    protected function _rss ($aArgs, $sClass = 'SearchResult')
    {
        $sMode = array_shift($aArgs);

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse())) {
            $this->_oTemplate->displayAccessDenied ($sMsg);
            exit;
        }

        $aParams = $this->_buildRssParams($sMode, $aArgs);

        bx_import ($sClass, $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . $sClass;
        $o = new $sClass($sMode, $aParams);

        if ($o->isError)
            $this->_oTemplate->displayPageNotFound ();
        else
            $o->outputRSS();

        exit;
    }

    protected function _getContent($iContentId = 0, $sFuncGetContent = true)
    {
        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;

        if($sFuncGetContent === true)
            $sFuncGetContent = 'getContentInfoById';

        if(empty($sFuncGetContent) || !method_exists($this->_oDb, $sFuncGetContent))
            return $iContentId;

        $aContentInfo = $this->_oDb->$sFuncGetContent($iContentId);
        if(!$aContentInfo)
            return false;

        return array($iContentId, $aContentInfo);
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]));

    	//--- Image(s)
        $aImages = $this->_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        $aImagesAttach = $this->_getImagesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

        //--- Video(s)
        $aVideos = $this->_getVideosForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        $aVideosAttach = $this->_getVideosForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

        //--- Files(s)
        $aFiles = $this->_getFilesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);
        $aFilesAttach = $this->_getFilesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

        //--- Title
        $sTitle = '';
        if(isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]))
            $sTitle = $aContentInfo[$CNF['FIELD_TITLE']];
        else if(isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]))
            $sTitle = strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

        //--- Text
        $sText = isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? $aContentInfo[$CNF['FIELD_TEXT']] : '';
        if(!empty($CNF['OBJECT_METATAGS']) && is_string($sText)) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            $sText = $oMetatags->metaParse($aContentInfo[$CNF['FIELD_ID']], $sText);
        }

    	return array(
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
            'sample_action' => isset($CNF['T']['txt_sample_action']) ? $CNF['T']['txt_sample_action'] : '',
            'url' => $sUrl,
            'title' => $sTitle,
            'text' => $sText,
            'images' => $aImages,
            'images_attach' => $aImagesAttach,
            'videos' => $aVideos,
            'videos_attach' => $aVideosAttach,
            'files' => $aFiles,
            'files_attach' => $aFilesAttach
        );
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
        if (bx_is_api()){
            $aResult = [];
            if(isset($CNF['FIELD_COVER']) && !empty($aContentInfo[$CNF['FIELD_COVER']])) {
                $aResult[] = bx_api_get_image($CNF['OBJECT_STORAGE'], (int)$aContentInfo[$CNF['FIELD_COVER']]);
            }
            if(isset($CNF['FIELD_THUMB']) && !empty($aContentInfo[$CNF['FIELD_THUMB']])) {
                $aResult[] = bx_api_get_image($CNF['OBJECT_STORAGE'], (int)$aContentInfo[$CNF['FIELD_THUMB']]);
            }
            return $aResult;
        }
        else{
            $iImageId = 0;
            $sImageSm = $sImageMd = $sImageXl = '';
            if(isset($CNF['FIELD_COVER']) && !empty($aContentInfo[$CNF['FIELD_COVER']])) {
                $iImageId = (int)$aContentInfo[$CNF['FIELD_COVER']];
                $sImageSm = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_MINIATURE', 'OBJECT_IMAGES_TRANSCODER_THUMB']);
                $sImageMd = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_THUMB']);
                $sImageXl = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_COVER']);
            }

            if($sImageMd == '' && isset($CNF['FIELD_THUMB']) && !empty($aContentInfo[$CNF['FIELD_THUMB']])) {
                $iImageId = (int)$aContentInfo[$CNF['FIELD_THUMB']];
                $sImageSm = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_MINIATURE', 'OBJECT_IMAGES_TRANSCODER_THUMB']);
                $sImageMd = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_THUMB']);
                $sImageXl = $this->_oConfig->getImageUrl($iImageId, ['OBJECT_IMAGES_TRANSCODER_COVER']);
            }

            if(empty($sImageMd))
                return [];

            if($sImageSm == '')
                $sImageSm = $sImageMd;

            if($sImageXl == '')
                $sImageXl = $sImageMd;

            return [[
                'id' => $iImageId, 
                'url' => $sUrl, 
                'src' => $sImageMd, 
                'src_small' => $sImageSm, 
                'src_medium' => $sImageMd, 
                'src_orig' => $sImageXl
            ]];
        }
    }

    protected function _getImagesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        return array();
    }

    protected function _getVideosForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        return array();
    }

    protected function _getVideosForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        return array();
    }

    protected function _getFilesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        return array();
    }

    protected function _getFilesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        return array();
    }

    protected function _entityComments ($sObject, $iId = 0)
    {
        if (!$iId)
            $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if (!$iId)
            return false;

        $oCmts = BxDolCmts::getObjectInstance($sObject, $iId);
        if (!$oCmts || !$oCmts->isEnabled())
            return false;
        
        if (bx_is_api()){
            return [bx_srv('system', 'get_data_api', [['module' => $sObject, 'object_id' => $iId]], 'TemplCmtsServices')];
        }
        return $oCmts->getCommentsBlock([], ['in_designbox' => false, 'show_empty' => false]);
    }

    protected function _getFields($iContentId)
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return array();

        return BxDolContentInfo::formatFields($aContentInfo);
    }

    protected function _getFieldValue($sField, $iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF[$sField]))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || empty($aContentInfo[$CNF[$sField]]))
            return false;

        return $aContentInfo[$CNF[$sField]];
    }

    protected function _getFieldValueThumb($sField, $iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($sTranscoder) || empty($CNF[$sField]))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || empty($aContentInfo[$CNF[$sField]]))
            return false;

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);
        if(!$oImagesTranscoder)
            return false;

        return $oImagesTranscoder->getFileUrl($aContentInfo[$CNF[$sField]]);
    }

	protected function _prepareResponse($aResponse, $bAsJson = false, $aAdditional = array())
    {
    	if(!$bAsJson)
    		return $aResponse;

		if(!empty($aAdditional) && is_array($aAdditional))
			$aResponse = array_merge($aResponse, $aAdditional);

		echoJson($aResponse);
		exit;
    }  
    
    /**
     * Blocks for lables tree browsing private functions
     */
    
    private function _getLablesBreadcrumbsArray($iLabelId, &$aBreadcrumbs)
	{
		$oLabel = BxDolLabel::getInstance();
		$aLabel = $oLabel->getLabels(array('type' => 'id', 'id' => $iLabelId));
		if ($aLabel){
            $aBreadcrumbs[$aLabel['id']] = array('id' => $aLabel['id'], 'value' => $aLabel['value']);
			$this->_getLablesBreadcrumbsArray($aLabel['parent'], $aBreadcrumbs);
		}
	}
    
    private function _getLablesBrowseUrl($iLabelId)
	{
        list($sPageLink, $aPageParams) = bx_get_base_url_inline();
		return BxDolPermalinks::getInstance()->permalink(bx_append_url_params($sPageLink, array_merge($aPageParams, array('label' => $iLabelId))));
	}
    
    private function _getLablesTreeLevel($aBreadcrumbs, $iParent = 0)
	{
		$oLabel = BxDolLabel::getInstance();
		$aLabelsOutput = array();
        $aLabels = $oLabel->getLabels(array('type' => 'parent', 'parent' => $iParent));
        if (count($aLabels) > 0){
			foreach($aLabels as $aLabel) {
				$sChild = $this->_getLablesTreeLevel($aBreadcrumbs, $aLabel['id']);
				$bChildPresent = $sChild != '' ? true : false;
				$bIsOpen = array_key_exists($aLabel['id'], $aBreadcrumbs) ? true : false;
				if (!$bIsOpen){
					$sChild = '';
				}
				$aLabelsOutput[] = array(
					'value' => $aLabel['value'], 
					'url' => $this->_getLablesBrowseUrl($aLabel['id']),
					'child' => $sChild,
					
					'selected' => bx_process_input(bx_get('label'), BX_DATA_INT) == $aLabel['id'] ? 'selected' : ''.$aLabel['id'], 
					'bx_if:open' => array(
						'condition' => $bChildPresent && $bIsOpen,
						'content' => array()
					),
					'bx_if:can_open' => array(
						'condition' => $bChildPresent && !$bIsOpen,
						'content' => array()
					)
				);
			}
			return $this->_oTemplate->parseHtmlByName('labels_tree.html', 
				array(
					'bx_repeat:items' => $aLabelsOutput,
					'parent' => $iParent,
				)
			);
		}
		return '';
	}
}

/** @} */
