<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_PROFILE_REDIRECT_PROFILE', 'profile');
define('BX_DOL_PROFILE_REDIRECT_LAST', 'last');
define('BX_DOL_PROFILE_REDIRECT_CUSTOM', 'custom');

/**
 * Base class for profile modules.
 */
class BxBaseModProfileModule extends BxBaseModGeneralModule implements iBxDolContentInfoService, iBxDolProfileService
{
    protected $_iAccountId;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;

        if(!empty($CNF['FIELD_ALLOW_POST_TO']))
            $this->_aSearchableNamesExcept[] = $CNF['FIELD_ALLOW_POST_TO'];

        $this->_iAccountId = getLoggedId();
    }

    public function actionDeleteProfileImg($iFileId, $iContentId, $sFieldPicture) 
    {
        $aResult = array();
        $CNF = &$this->_oConfig->CNF;

        $oSrorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!($aFile = $oSrorage->getFile((int)$iFileId)) || !($aContentInfo = $this->_oDb->getContentInfoById($iContentId)) || $aContentInfo[$sFieldPicture] != (int)$iFileId)
            $aResult = array('error' => 1, 'msg' => _t('_sys_storage_err_file_not_found'));

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $iAccountProfileId = $oAccountProfile->id();

        if ((!$aResult && !isLogged()) || (!$aResult && $aFile['profile_id'] != $iAccountProfileId && !$this->_isModerator()))           
            $aResult = array('error' => 2, 'msg' => _t('_Access denied'));

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);

        if (!$aResult && !$oForm->_deleteFile($iContentId, $sFieldPicture, (int)$iFileId, true))
            $aResult = array('error' => 3, 'msg' => _t('_Failed'));
        elseif (!$aResult)            
            $aResult = array('error' => 0, 'msg' => '');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aResult);
    }

    // ====== SERVICE METHODS
    public function serviceGetOptionsRedirectAfterAdd()
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = array();
        $aChoices = array(BX_DOL_PROFILE_REDIRECT_PROFILE, BX_DOL_PROFILE_REDIRECT_LAST, BX_DOL_PROFILE_REDIRECT_CUSTOM);
        foreach($aChoices as $sChoice) 
            $aResult[] = array('key' => $sChoice, 'value' => _t($CNF['T']['option_redirect_aadd_' . $sChoice]));

        return $aResult;
    }

    public function serviceGetThumb ($iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($sTranscoder) && !empty($CNF['OBJECT_IMAGES_TRANSCODER_THUMB']))
            $sTranscoder = $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'];

        $mixedResult = $this->_getFieldValueThumb('FIELD_PICTURE', $iContentId, $sTranscoder);
        return $mixedResult !== false ? $mixedResult : '';
    }

    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit_with_cover.html';

        return parent::serviceGetSearchResultUnit($iContentId, $sUnitTemplate);
    }

    public function serviceGetSearchableFieldsExtended($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetSearchableFieldsExtended($aInputsAdd);
        if(!$this->serviceActAsProfile())
            return $aResult;

        if(!in_array('online', $this->_aSearchableNamesExcept))
            $aResult['online'] = array(
                'type' => 'checkbox', 
                'caption' => $CNF['T']['form_field_online'],
                'info' => '',
            	'value' => '1',
                'values' => '',
                'pass' => ''
            );

        if(!empty($CNF['FIELD_PICTURE']) && !in_array($CNF['FIELD_PICTURE'], $this->_aSearchableNamesExcept))
            $aResult[$CNF['FIELD_PICTURE']] = array(
                'type' => 'checkbox', 
                'caption' => $CNF['T']['form_field_picture'],
                'info' => '',
            	'value' => '1',
                'values' => '',
                'pass' => '',
                'search_operator' => '>=' 
            );

        return $aResult;
    }

	public function servicePrivateProfileMsg()
    {
        $mixedContent = $this->_getContent();
        if ($mixedContent) {
            list($iContentId, $aContentInfo) = $mixedContent;
            return $this->checkAllowedView($aContentInfo);
        }
        return MsgBox(_t('_sys_access_denied_to_private_content'));
    }
    
	public function serviceGetContentInfoById($iContentId)
    {
        return $this->_oDb->getContentInfoById((int)$iContentId);
    }

	public function serviceGetMenuAddonManageTools()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->unsetPaginate();
        $iNumTotal = $o->getNum();
        
        $o->fillFilters(array(
			'perofileStatus' => BX_PROFILE_STATUS_PENDING
        ));
        $iNum1 = $o->getNum();
        
        $iNum2 = 0;
        $CNF = &$this->_oConfig->CNF;
        if (isset($CNF['OBJECT_REPORTS'])){
            $o->fillFilters(array('perofileStatus' => ''));
            $o->fillFiltersByObjects(array('reported' => array('value' => '0', 'field' => 'reports', 'operator' => '>', 'table' => $CNF['TABLE_ENTRIES'])));
            $iNum2 = $o->getNum();
        }
        return array('counter1_value' => $iNum1, 'counter2_value' => $iNum2, 'counter3_value' => $iNumTotal, 'counter1_caption' => _t('_sys_menu_dashboard_manage_tools_addon_counter1_caption_profile_default'));
	}

	public function serviceGetMenuAddonManageToolsProfileStats()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'account_id' => getLoggedId(),
        	'perofileStatus' => ''
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

    public function serviceGetMenuAddonFavoritesProfileStats()
    {
    	bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass('favorite', array('user' => bx_get_logged_profile_id()));
        $o->unsetPaginate();

        return $o->getNum();
    }

    public function serviceGetSubmenuObject ()
    {
        return $this->_oConfig->CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'];
    }

    public function serviceGetMenuSetNameForMenuTrigger ($sMenuTriggerName)
    {
        $CNF = &$this->_oConfig->CNF;

        if (isset($CNF['TRIGGER_MENU_PROFILE_VIEW_SUBMENU']) && $CNF['TRIGGER_MENU_PROFILE_VIEW_SUBMENU'] == $sMenuTriggerName)
            return $CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'];
        elseif (isset($CNF['TRIGGER_MENU_PROFILE_SNIPPET_META']) && $CNF['TRIGGER_MENU_PROFILE_SNIPPET_META'] == $sMenuTriggerName)
            return $CNF['OBJECT_MENU_SNIPPET_META'];
        else if (isset($CNF['TRIGGER_MENU_PROFILE_VIEW_ACTIONS']) && $CNF['TRIGGER_MENU_PROFILE_VIEW_ACTIONS'] == $sMenuTriggerName) {
            if(empty($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL']))
                return $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'];
            else
                return array($CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'], $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL']);
        }

        return '';
    }

    public function serviceGetSnippetMenuVars($iProfileId, $bPublic = null)
    {
        return $this->_oTemplate->getSnippetMenuVars($iProfileId, $bPublic);
    }
        
	public function serviceGetPageObjectForPageTrigger ($sPageTriggerName)
    {
        if (isset($this->_oConfig->CNF['TRIGGER_PAGE_VIEW_ENTRY']) && $this->_oConfig->CNF['TRIGGER_PAGE_VIEW_ENTRY'] == $sPageTriggerName)
        	return $this->_oConfig->CNF['OBJECT_PAGE_VIEW_ENTRY'];

        return '';
    }

    public function serviceProfilesSearch ($sTerm, $iLimit)
    {
		$aRet = array();

        $a = $this->_oDb->searchByTerm($sTerm, $iLimit);
        foreach ($a as $r) {
            $oProfile = BxDolProfile::getInstance($r['profile_id']);

            $aRet[] = array (
            	'label' => $this->serviceProfileName($r['content_id']), 
                'value' => $r['profile_id'], 
                'url' => $oProfile->getUrl(),
            	'thumb' => $oProfile->getThumb(),
                'unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
            );
        }

        return $aRet;
    }

    public function serviceProfileUnit ($iContentId, $aParams = array())
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        $bCheckPrivateContent = isset($aParams['check_private_content']) ? (bool)$aParams['check_private_content'] : true;

        $sTemplate = 'unit.html';
        $sTemplateSize = false;
        $aTemplateVars = array();
        if(!empty($aParams['template'])) {
            if(is_string($aParams['template']))
                $sTemplate = $aParams['template'] . '.html';
            else if(is_array($aParams['template'])) {
                if(!empty($aParams['template']['name']))
                    $sTemplate = $aParams['template']['name'] . '.html';

                if(!empty($aParams['template']['size']))
                    $sTemplateSize = $aParams['template']['size'];

                if(!empty($aParams['template']['vars']))
                    $aTemplateVars = $aParams['template']['vars'];
            }
        }

        return $this->_oTemplate->unit($aContentInfo, $bCheckPrivateContent, array($sTemplate, $sTemplateSize, $aTemplateVars));
    }

    public function serviceHasImage ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $mixedContent = $this->_getContent($iContentId, 'getContentInfoById');
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        return !empty($aContentInfo[$CNF['FIELD_PICTURE']]);
    }

    public function serviceProfilePicture ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlPicture', $iContentId);
    }

    public function serviceProfileAvatar ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlAvatar', $iContentId);
    }
    
    public function serviceProfileCover ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlCover', $iContentId);
    }

    public function serviceProfileUnitCover ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlCoverUnit', $iContentId);
    }

    public function serviceProfileEditUrl ($iContentId)
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_EDIT_ENTRY'] . '&id=' . $iContentId);
    }

    public function serviceProfileThumb ($iContentId)
    {
        return $this->_serviceTemplateFunc('thumb', $iContentId);
    }

    public function serviceProfileIcon ($iContentId)
    {
        return $this->_serviceTemplateFunc('icon', $iContentId);
    }

    public function serviceProfileName ($iContentId)
    {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        return bx_process_output($aContentInfo[$this->_oConfig->CNF['FIELD_NAME']]);
    }

    public function serviceProfileCreateUrl ($bAbsolute = true)
    {
    	$CNF = $this->_oConfig->CNF;
    	if(empty($CNF['URL_CREATE']))
    		return false;

    	return $bAbsolute ? BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_CREATE']) : $CNF['URL_CREATE'];
    }

    public function serviceProfileUrl ($iContentId)
    {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        $CNF = $this->_oConfig->CNF;
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
    }

    /**
     * @see iBxDolProfileService::serviceGetSpaceTitle
     */ 
    public function serviceGetSpaceTitle()
    {
        return _t('_sys_ps_space_title_friend');
    }

    /**
     * @see iBxDolProfileService::serviceGetParticipatingProfiles
     */ 
    public function serviceGetParticipatingProfiles($iProfileId, $aConnectionObjects = false)
    {
        if (false === $aConnectionObjects)
            $aConnectionObjects = array('sys_profiles_friends');

        $a = array();
        foreach ($aConnectionObjects as $sConnectionObject) {
            if (!($o = BxDolConnection::getObjectInstance($sConnectionObject)))
                continue;

            if (BX_CONNECTIONS_TYPE_MUTUAL == $o->getType())
                $a = array_merge($a, $o->getConnectedContent($iProfileId, true));
            else
                $a = array_merge($a, $o->getConnectedContent($iProfileId));
        }

        $a = array_unique($a);
        $aRet = array();
        foreach ($a as $iConnectedProfileId) {
            if (!($oConnectedProfile = BxDolProfile::getInstance($iConnectedProfileId)))
                continue;
            if ($oConnectedProfile->getModule() != $this->getName())
                continue;

            if (CHECK_ACTION_RESULT_ALLOWED === bx_srv($oConnectedProfile->getModule(), 'check_allowed_post_in_profile', array($oConnectedProfile->getContentId(), $iProfileId)))
                $aRet[] = $iConnectedProfileId;
        }

        bx_alert('system', 'get_participating_profiles', $iProfileId, false, array(
            'module' => $this->_oConfig->getName(),
            'profiles' => &$aRet
        ));

        return $aRet;
    }

    /**
     * Prepare fields from some universal set of fields to fields in particular profile module. 
     * By default only 'name' and 'description' fields are supported.
     * After fields convertion it can be used in @see BxBaseModGeneralModule::serviceEntityAdd
     * @param $aFieldsProfile fields in soem universal format.
     * @return array which is ready to use for particular module
     */ 
    public function servicePrepareFields ($aFieldsProfile)
    {        
        bx_alert($this->getName(), 'prepare_fields', 0, 0, array('fields_orig' => $aFieldsProfile, 'fields_result' => &$aFieldsProfile));

        return $aFieldsProfile;
    }

    protected function _servicePrepareFields ($aFieldsProfile, $aFieldsDefault, $aMap)
    {
        $aFieldsOrig = $aFieldsProfile;

        bx_import('BxDolPrivacy');
        $aFieldsDefault2 = array(
            'allow_view_to' => BX_DOL_PG_ALL,
            'allow_post_to' => BX_DOL_PG_FRIENDS,
        );
        $aFieldsProfile = array_merge($aFieldsDefault2, $aFieldsDefault, $aFieldsProfile);

        foreach ($aMap as $k => $v) {
            if (isset($aFieldsProfile[$v]))
                $aFieldsProfile[$k] = $aFieldsProfile[$v];
            if ($k != $v && !isset($aMap[$v]))
                unset($aFieldsProfile[$v]);
        }
        
        bx_alert($this->getName(), 'prepare_fields', 0, 0, array('fields_orig' => $aFieldsOrig, 'fields_result' => &$aFieldsProfile));

        return $aFieldsProfile;
    }

    public function serviceFormsHelper ()
    {
        return parent::serviceFormsHelper ();
    }

    public function serviceEntityCreate ($sDisplay = false)
    {
	    BxDolInformer::getInstance($this->_oTemplate)->setEnabled(false);

	    return parent::serviceEntityCreate ($sDisplay);
    }

    public function serviceActAsProfile ()
    {
        return true;
    }

    public function serviceBrowseRecommended ($sUnitView = false, $bEmptyMessage = false, $bAjaxPaginate = true)
    {
        if (!isLogged())
            return '';
        return $this->_serviceBrowse ('recommended', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    public function serviceBrowseRecentProfiles ($bDisplayEmptyMsg = false, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('recent', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    public function serviceBrowseActiveProfiles ($sUnitView = false, $bEmptyMessage = false, $bAjaxPaginate = false)
    {
        return $this->_serviceBrowse ('active', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    public function serviceBrowseTopProfiles ($bDisplayEmptyMsg = false, $bAjaxPaginate = false)
    {
        return $this->_serviceBrowse ('top', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    public function serviceBrowseOnlineProfiles ($bDisplayEmptyMsg = false, $bAjaxPaginate = false)
    {
        return $this->_serviceBrowse ('online', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    public function serviceBrowseConnections ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iDesignBox = BX_DB_PADDING_DEF, $iProfileId2 = 0)
    {
        return $this->_serviceBrowse (
            'connections',
            array(
                'object' => $sObjectConnections,
                'type' => $sConnectionsType,
                'mutual' => $iMutual,
                'profile' => (int)$iProfileId,
                'profile2' => (int)$iProfileId2),
            $iDesignBox
        );
    }

    public function serviceBrowseRelationsQuick ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iProfileId2 = 0)
    {
        // get connections object
        $oConnection = BxDolConnection::getObjectInstance($sObjectConnections);
        if (!$oConnection)
            return '';       

        // set some vars
        $iStart = (int)bx_get('start');
        $iLimit = empty($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? 4 : getParam($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']);
        if (!$iLimit)
            $iLimit = 4;

        // get connections array
        bx_import('BxDolConnection');
        $aConnections = $oConnection->getConnectionsAsArrayExt($sConnectionsType, $iProfileId, $iProfileId2, $iMutual, $iStart, $iLimit + 1, BX_CONNECTIONS_ORDER_ADDED_DESC);
        if(empty($aConnections) || !is_array($aConnections))
            return '';

        $aResult = array();
        foreach($aConnections as $iProfile => $aConnection)
            $aResult[] = array(
                'id' => $iProfile,
                'info' => array(
                    'addon' => $oConnection->getRelationTranslation($aConnection['relation'])
                )
            );

        return $this->_serviceBrowseQuick($aResult, $iStart, $iLimit);
    }

    public function serviceBrowseConnectionsQuick ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iProfileId2 = 0)
    {
        // get connections object
        $oConnection = BxDolConnection::getObjectInstance($sObjectConnections);
        if (!$oConnection)
            return '';

        // set some vars
        $iLimit = empty($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? 4 : getParam($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']);
        if (!$iLimit)
            $iLimit = 4;
        $iStart = (int)bx_get('start');

        // get connections array
        bx_import('BxDolConnection');
        $a = $oConnection->getConnectionsAsArray ($sConnectionsType, $iProfileId, $iProfileId2, $iMutual, (int)bx_get('start'), $iLimit + 1, BX_CONNECTIONS_ORDER_ADDED_DESC);
        if (!$a)
            return '';

        return $this->_serviceBrowseQuick($a, $iStart, $iLimit);
    }

    public function serviceBrowseConnectionsByType ($aParamsCnn, $aParamsBrs = array())
    {
        $sAll = 'all';

        $aParamsCnn['object'] = !empty($aParamsCnn['object']) ? $aParamsCnn['object'] : 'sys_profiles_friends';
        $aParamsCnn['type'] = !empty($aParamsCnn['type']) ? $aParamsCnn['type'] : 'content';
        $aParamsCnn['profile_id2'] = !empty($aParamsCnn['profile_id2']) ? (int)$aParamsCnn['profile_id2'] : 0;
        $aParamsCnn['mutual'] = isset($aParamsCnn['mutual']) ? $aParamsCnn['mutual'] : false;

        $oConnection = BxDolConnection::getObjectInstance($aParamsCnn['object']);
        if(!$oConnection)
            return '';

        $aSQLParts = $oConnection->getConnectionsAsSQLParts ($aParamsCnn['type'], 'sys_profiles', 'id', $aParamsCnn['profile_id'], $aParamsCnn['profile_id2'], $aParamsCnn['mutual']);

        $sType = !empty($aParamsBrs['type']) ? $aParamsBrs['type'] : $sAll;
        if(bx_get('type') !== false)
            $sType = bx_process_input(bx_get('type'));

        $iStart = (int)bx_get('start');

        $iLimitDefault = 4;
        $iLimit = !empty($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? getParam($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']) : $iLimitDefault;
        if(!empty($aParamsBrs['per_page']))
            $iLimit = (int)$aParamsBrs['per_page'];
        if(!$iLimit)
            $iLimit = $iLimitDefault;

        $aProfiles = BxDolProfileQuery::getInstance()->getConnectedProfilesByType($aSQLParts, ($sType != $sAll ? $sType : ''), $iStart, $iLimit + 1);

        $mixedMenu = '';
        if(!isset($aParamsBrs['filter_menu']) || $aParamsBrs['filter_menu'] === true) {
            $aModules = array(
                array('name' => $sAll)
            );
            $aModules = array_merge($aModules, BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1)));

            $aMenuItems = array();
            foreach($aModules as $aModule)
                if($aModule['name'] == $sAll || BxDolRequest::serviceExists($aModule['name'], 'act_as_profile'))
                    $aMenuItems[] = array(
                    	'id' => $aModule['name'], 
                    	'name' => $aModule['name'], 
                    	'class' => '', 
                    	'link' => 'javascript:void(0)', 
                    	'onclick' => "return !loadDynamicBlockAutoPaginate(this, " . $iStart . ", " . $iLimit . ", " . bx_js_string(json_encode(array('type' => $aModule['name']))) . ");", 
                    	'target' => '_self', 
                    	'title' => _t('_' . $aModule['name']), 
                    	'active' => 1
                    );
    
            if(!empty($aMenuItems)) {
                $mixedMenu = new BxTemplMenu(array('template' => 'menu_vertical.html', 'menu_id'=> $this->_oConfig->getName() . '-connections-by-type', 'menu_items' => $aMenuItems));
                $mixedMenu->setSelected('', $sType);
            }
        }

        return array(
            'menu' => $mixedMenu,
            'content' => $this->_serviceBrowseQuick(array_keys($aProfiles), $iStart, $iLimit, array('type' => $sType))
        );
    }

	public function serviceBrowseByAcl ($mixedLevelId, $iDesignBox = BX_DB_PADDING_DEF)
    {
        return $this->_serviceBrowse (
            'acl',
            array(
                'level' => $mixedLevelId,
			),
            $iDesignBox,
            true
        );
    }

    public function serviceEntityEditCover ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('editDataForm', $iContentId, $this->_oConfig->CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER']);
    }

	/**
     * Entry comments
     */
    public function serviceEntityCommentsByProfile ($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_COMMENTS']))
            return '';

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile)
            return '';

        return $this->_entityComments($CNF['OBJECT_COMMENTS'], $oProfile->getContentId());
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($mixedContent = false, $aParams = array())
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

        return parent::serviceEntitySocialSharing(array($iContentId, $aContentInfo), array(
            'id_thumb' => !empty($CNF['FIELD_PICTURE']) && !empty($aContentInfo[$CNF['FIELD_PICTURE']]) ? $aContentInfo[$CNF['FIELD_PICTURE']] : 0, 
        ));
    }

    public function serviceProfileMembership ($iContentId = 0)
    {
    	$mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

		return BxDolAcl::getInstance()->getProfileMembership($aContentInfo['profile_id']);
    }

    public function serviceProfileFriends ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseConnectionsQuick ($aContentInfo['profile_id'], 'sys_profiles_friends', BX_CONNECTIONS_CONTENT_TYPE_CONTENT, true);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));
        return $s;
    }

    public function serviceProfileSubscriptions ($iContentId = 0, $aParams = array())
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        bx_import('BxDolConnection');
        $aResult = $this->serviceBrowseConnectionsByType(array(
        	'profile_id' => $aContentInfo['profile_id'],  
        	'object' => 'sys_profiles_subscriptions', 
            'type' => BX_CONNECTIONS_CONTENT_TYPE_CONTENT
        ), array(
            'type' => isset($aParams['type']) ? $aParams['type'] : '',
            'filter_menu' => isset($aParams['filter_menu']) ? $aParams['filter_menu'] : true,
        ));
        if(empty($aResult['content']))
            $aResult['content'] = MsgBox(_t('_sys_txt_empty'));

        return $aResult;
    }

    public function serviceProfileSubscribedMe ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseConnectionsQuick ($aContentInfo['profile_id'], 'sys_profiles_subscriptions', BX_CONNECTIONS_CONTENT_TYPE_INITIATORS);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));

        return $s;
    }

    public function serviceProfileRelations ($iContentId = 0, $aParams = array())
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseRelationsQuick ($aContentInfo['profile_id'], 'sys_profiles_relations', BX_CONNECTIONS_CONTENT_TYPE_CONTENT, 1);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));

        return $s;
    }

    public function serviceProfileRelatedMe ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseRelationsQuick ($aContentInfo['profile_id'], 'sys_profiles_relations', BX_CONNECTIONS_CONTENT_TYPE_INITIATORS, 1);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));

        return $s;
    }

    /**
     * check enabled profile activation letter
     */
    public function  serviceIsEnableProfileActivationLetter()
    {
        $CNF = &$this->_oConfig->CNF;
        return isset($CNF['PARAM_ENABLE_ACTIVATION_LETTER']) ? (bool)getParam($CNF['PARAM_ENABLE_ACTIVATION_LETTER']) : true;
    }

    public function serviceIsEnableRelations()
    {
        $sModule = $this->_oConfig->getName();
        $oRelations = BxDolConnection::getObjectInstance('sys_profiles_relations');
        return $oRelations->isRelationAvailableWithProfile($sModule) || $oRelations->isRelationAvailableFromProfile($sModule);
    }

    /**
     * For internal usage only.
     */
    public function serviceDeleteEntityService ($iContentId, $bDeleteWithContent = false)
    {
        return parent::serviceDeleteEntity ($iContentId, 'deleteDataService');
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
        $a = parent::serviceGetNotificationsData();

        $sModule = $this->_aModule['name'];
        
        $a['handlers'][] = array('group' => $sModule . '_timeline_post_common', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'timeline_post_common', 'module_name' => $sModule, 'module_method' => 'get_notifications_timeline_post_common', 'module_class' => 'Module');

        $a['settings'][] = array('group' => 'timeline_post', 'unit' => $sModule, 'action' => 'timeline_post_common', 'types' => array('follow_member'));

        $a['alerts'][] = array('unit' => $sModule, 'action' => 'timeline_post_common');

        return $a;
    }

    public function serviceGetNotificationsPost($aEvent)
    {
        $aResult = parent::serviceGetNotificationsVote($aEvent);
        if(empty($aResult) || !is_array($aResult) || !$this->serviceActAsProfile())
            return $aResult;

        $oProfile = BxDolProfile::getInstanceByContentAndType((int)$aEvent['object_id'], $this->_oConfig->getName());
        if($oProfile !== false)
            $aResult['entry_author'] = $oProfile->id();

        return $aResult;
    }

    public function serviceGetNotificationsVote($aEvent)
    {
        $aResult = parent::serviceGetNotificationsVote($aEvent);
        if(empty($aResult) || !is_array($aResult) || !$this->serviceActAsProfile())
            return $aResult;

        $oProfile = BxDolProfile::getInstanceByContentAndType((int)$aEvent['object_id'], $this->_oConfig->getName());
        if($oProfile !== false)
            $aResult['entry_author'] = $oProfile->id();

        return $aResult;
    }

    /**
     * Notification about new member requst in the group
     */
    public function serviceGetNotificationsTimelinePostCommon($aEvent)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if(!$oGroupProfile)
            return array();

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();
            
        $aSubcontentInfo = BxDolService::call('bx_timeline', 'get_info', array((int)$aEvent['subobject_id'], false));
        if(empty($aSubcontentInfo) || !is_array($aSubcontentInfo))
            return array();

        $sSubentryUrl = BxDolService::call('bx_timeline', 'get_link', array((int)$aEvent['subobject_id']));
        $sSubentrySample = $aSubcontentInfo['title'];
        if(empty($sSubentrySample))
            $sSubentrySample = strmaxtextlen($aSubcontentInfo['description'], 20, '...');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $oGroupProfile->getUrl(),
            'entry_caption' => $oGroupProfile->getDisplayName(),
            'entry_author' => $oGroupProfile->id(),
            'subentry_sample' => $sSubentrySample,
            'subentry_url' => $sSubentryUrl,
            'lang_key' => $CNF['T']['txt_ntfs_timeline_post_common'],
        );
    }

	/**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
        $CNF = &$this->_oConfig->CNF;
        $sModule = $this->_aModule['name'];

        $aAlerts = array();
        $aHandlers = array();
        if(!empty($CNF['FIELD_PICTURE'])) {
            $aAlerts = array_merge($aAlerts, array(
                array('unit' => $sModule, 'action' => 'profile_picture_changed'),
                array('unit' => $sModule, 'action' => 'profile_picture_deleted')
            ));
            $aHandlers = array_merge($aHandlers, array(
                array('group' => $sModule . '_profile_picture', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'profile_picture_changed', 'module_name' => $sModule, 'module_method' => 'get_timeline_profile_picture', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_profile_picture', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'profile_picture_deleted')
            ));
        }

        if(!empty($CNF['FIELD_COVER'])) {
            $aAlerts = array_merge($aAlerts, array(
                array('unit' => $sModule, 'action' => 'profile_cover_changed'),
                array('unit' => $sModule, 'action' => 'profile_cover_deleted')
            ));
            $aHandlers = array_merge($aHandlers, array(
                array('group' => $sModule . '_profile_cover', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'profile_cover_changed', 'module_name' => $sModule, 'module_method' => 'get_timeline_profile_cover', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_profile_cover', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'profile_cover_deleted')
            ));
        }

    	$aResult = array();
        if(!empty($aAlerts) && !empty($aHandlers))
            $aResult = array(
            	'handlers' => $aHandlers,
            	'alerts' => $aAlerts
            );

        return $aResult;
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelineProfilePicture($aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_serviceGetTimelineProfileImage($aEvent, $aBrowseParams, array(
            'stg' => 'OBJECT_STORAGE',
            'trans' => array('OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_AVATAR'),
            'trans_orig' => array('OBJECT_IMAGES_TRANSCODER_PICTURE', 'OBJECT_IMAGES_TRANSCODER_GALLERY'),
            'txt_ss' => 'txt_sample_pp_single',
            'txt_sswa' => 'txt_sample_pp_single_with_article',
            'txt_sa' => 'txt_sample_pi_action',
            'txt_sau' => 'txt_sample_pi_action_user'
        ));
        $aResult['allowed_view'] = array('module' => $this->_oConfig->getName(), 'method' => 'get_timeline_profile_picture_allowed_view');

        return $aResult;
    }

    public function serviceGetTimelineProfilePictureAllowedView($aEvent)
    {
        return $this->_serviceGetTimelineProfileImageAllowedView($aEvent);
    }

    public function serviceGetTimelineProfileCover($aEvent, $aBrowseParams = array())
    {
        $aResult = $this->_serviceGetTimelineProfileImage($aEvent, $aBrowseParams, array(
            'stg' => 'OBJECT_STORAGE_COVER',
            'trans' => array('OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_COVER_THUMB'),
            'trans_orig' => array('OBJECT_IMAGES_TRANSCODER_COVER', 'OBJECT_IMAGES_TRANSCODER_GALLERY'),
            'txt_ss' => 'txt_sample_pc_single',
            'txt_sswa' => 'txt_sample_pc_single_with_article',
            'txt_sa' => 'txt_sample_pi_action',
            'txt_sau' => 'txt_sample_pi_action_user'
        ));
        $aResult['allowed_view'] = array('module' => $this->_oConfig->getName(), 'method' => 'get_timeline_profile_cover_allowed_view');

        return $aResult;
    }
    
    public function serviceGetTimelineProfileCoverAllowedView($aEvent)
    {
        return $this->_serviceGetTimelineProfileImageAllowedView($aEvent);
    }

    public function serviceGetConnectionButtonsTitles($iProfileId, $sConnectionsObject = 'sys_profiles_friends')
    {
        if (!isLogged())
            return array();

        if (!($oConn = BxDolConnection::getObjectInstance($sConnectionsObject)))
            return array();

        $CNF = $this->_oConfig->CNF;

        if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $iProfileId)) {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend_sent']),
                'remove' => _t($CNF['T']['menu_item_title_unfriend_cancel_request']),
            );
        } elseif ($oConn->isConnectedNotMutual($iProfileId, bx_get_logged_profile_id())) {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend_confirm']),
                'remove' => _t($CNF['T']['menu_item_title_unfriend_reject_request']),
            );
        } elseif ($oConn->isConnected($iProfileId, bx_get_logged_profile_id(), true)) {
            return array(
                'add' => '',
                'remove' => _t($CNF['T']['menu_item_title_unfriend']),
            );
        } else {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend']),
                'remove' => '',
            );
        }
    }


    // ====== PERMISSION METHODS
    /**
     * @see iBxDolProfileService::serviceCheckAllowedProfileView
     */ 
    public function serviceCheckAllowedProfileView($iContentId)
    {        
        return $this->serviceCheckAllowedWithContent('View', $iContentId);
    }

    /**
     * @see iBxDolProfileService::serviceCheckAllowedPostInProfile
     */
    public function serviceCheckAllowedPostInProfile($iContentId)
    {
        return $this->serviceCheckAllowedWithContent('Post', $iContentId);
    }

    /**
     * @see iBxDolProfileService::serviceCheckSpacePrivacy
     */ 
    public function serviceCheckSpacePrivacy($iContentId)
    {
        return $this->serviceCheckAllowedProfileView($iContentId);
    }

    /**
     * Check if the profile can be viewed.
     * 
     * NOTE. This service should be used if it's needed to pass some specific values in 
     * $isPerformAction and $iProfileId parameters, otherwise it's recommended to use 
     * BxBaseModProfileModule::serviceCheckAllowedProfileView service method or 
     * BxDolProfile::checkAllowedProfileView method.
     * 
     * @param type $aDataEntry - entry which the action will be performed for
     * @param type $isPerformAction - perform or just check the action
     * @param type $iProfileId - performer's profile ID
     * @return integer - one of CHECK_ACTION_RESULT_XXX constants.
     */
    public function serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction = false, $iProfileId = false)
    {
        if (!$iProfileId)
            $iProfileId = $this->_iProfileId;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
        if ($oProfile && $oProfile->id() == $iProfileId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId);
    }

    /**
     * Check if posting (comment, post in Timeline) is available.
     * 
     * NOTE. This service should be used if it's needed to pass some specific values in 
     * $isPerformAction and $iProfileId parameters, otherwise it's recommended to use 
     * BxBaseModProfileModule::serviceCheckAllowedPostInProfile service method or 
     * BxDolProfile::checkAllowedPostInProfile method.
     * 
     * @param type $aDataEntry - entry which the action will be performed for
     * @param type $isPerformAction - perform or just check the action
     * @param type $iProfileId - performer's profile ID
     * @return integer - one of CHECK_ACTION_RESULT_XXX constants.
     */
    public function serviceCheckAllowedPostForProfile ($aDataEntry, $isPerformAction = false, $iProfileId = false)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileId)
            $iProfileId = $this->_iProfileId;

        // check is view allowed
        if(($mixedResult = $this->serviceCheckAllowedViewForProfile($aDataEntry, $isPerformAction, $iProfileId)) !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        // moderator and owner always have access
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->getName());
        if(($oProfile && $oProfile->id() == $iProfileId) || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check privacy
        if(!empty($CNF['OBJECT_PRIVACY_POST'])) {
            $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_POST']);
            if($oPrivacy && !$oPrivacy->check($aDataEntry[$CNF['FIELD_ID']], $iProfileId))
                return _t('_sys_access_denied_to_private_content');
        }

        // check alert to allow custom checks
        $mixedResult = null;
        bx_alert('system', 'check_allowed_post', 0, 0, array('module' => $this->getName(), 'content_info' => $aDataEntry, 'profile_id' => $iProfileId, 'override_result' => &$mixedResult));
        if($mixedResult !== null)
            return $mixedResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function serviceSetViewProfileCover($oPage, $aProfileInfo)
    {
        $this->_oTemplate->setCover($oPage,$aProfileInfo);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedAdd ($isPerformAction = false)
    {
        $oAccount = BxDolAccount::getInstance();
        if (!$oAccount || ($this->serviceActAsProfile() && $oAccount->isProfilesLimitReached()))
            return _t('_sys_txt_access_denied');
        return parent::checkAllowedAdd ($isPerformAction);
    }   

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        return $this->serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewProfileImage ($aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        // check privacy
        if (empty($CNF['OBJECT_PRIVACY_VIEW'])) 
            return CHECK_ACTION_RESULT_ALLOWED;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        if ($oPrivacy && !$oPrivacy->check($aDataEntry[$CNF['FIELD_ID']]) && !$oPrivacy->isPartiallyVisible($aDataEntry[$CNF['FIELD_ALLOW_VIEW_TO']]))
            return _t('_sys_access_denied_to_private_content');

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewCoverImage ($aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowedViewProfileImage($aDataEntry);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedPost ($aDataEntry, $isPerformAction = false)
    {
        return $this->serviceCheckAllowedPostForProfile ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * Check if user can change cover image
     */
    public function checkAllowedChangeCover ($aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL and owner (checked by account! not as profile as ususal)
        $aCheck = checkActionModule($this->_iProfileId, 'delete entry', $this->getName(), $isPerformAction);

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name'], $isPerformAction);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewMoreMenu (&$aDataEntry, $isPerformAction = false)
    {
        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']);
        if (!$oMenu || !$oMenu->getCode())
            return _t('_sys_txt_access_denied');
        return CHECK_ACTION_RESULT_ALLOWED;
    }

	/**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedCompose (&$aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->getName());
        if($oProfile && $oProfile->id() == $this->_iProfileId)
            return _t('_sys_txt_access_denied');

        return $this->checkAllowedView ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFriendAdd (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFriendRemove (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', false, true, true))
            return CHECK_ACTION_RESULT_ALLOWED;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', false, true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedRelationAdd (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aDataEntry)))
            return $sMsg;

        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_relations', false, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedRelationRemove (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_relations', false, true);
    }

    public function checkAllowedRelationsView (&$aDataEntry, $isPerformAction = false)
    {
        $sResult = _t('_sys_txt_access_denied');

        $sModule = $this->_oConfig->getName();
        $oRelations = BxDolConnection::getObjectInstance('sys_profiles_relations');
        if(!$oRelations->isRelationAvailableWithProfile($sModule) && !$oRelations->isRelationAvailableFromProfile($sModule))
            return $sResult;

        if(empty($aDataEntry) || !is_array($aDataEntry))
            return $sResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aDataEntry)))
            return $sMsg;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedSubscribeRemove (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, true);
    }

    public function checkAllowedSubscriptionsView (&$aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $sResult = _t('_sys_txt_access_denied');
        if(empty($aDataEntry) || !is_array($aDataEntry))
            return $sResult;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->_aModule['name']);
        if(!$oProfile || ($oProfile->id() != $this->_iProfileId && $this->_oDb->getParam($CNF['PARAM_PUBLIC_SBSN']) != 'on' && $this->_oDb->getParam($CNF['PARAM_PUBLIC_SBSD']) != 'on'))
            return $sResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkMyself($iContentId)
    {
		$iLogged = (int)bx_get_logged_profile_id();
    	if(empty($iLogged))
    		return false;

    	$oProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->_oConfig->getName());
    	if(!$oProfile)
    		return false;

		return $oProfile->id() == $iLogged;
    }

    // ====== COMMON METHODS
    public function alertAfterAdd($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $sModule = $this->getName();
        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $aParams = array();        
        if(isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]))
            $aParams['privacy_view'] = $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']];

        bx_alert($sModule, 'added', $iContentId, false, $aParams);
    }

    public function alertAfterEdit($aContentInfo)
    {
        $CNF = &$this->_oConfig->CNF;

        $sModule = $this->getName();
        $iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

        $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $sModule);

        bx_alert($sModule, 'edited', $iContentId);
        bx_alert('profile', 'edit', $oProfile->id(), 0, array('content' => $iContentId, 'module' => $sModule));
    }

    public function getProfileByCurrentUrl ()
    {
        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        
        if ($iProfileId)
            return  BxDolProfile::getInstance($iProfileId);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iContentId)
            return BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());
        
        return false;
    }

    // ====== PROTECTED METHODS

    protected function _checkAllowedConnect (&$aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap = false)
    {
        if (!$this->_iProfileId)
            return _t('_sys_txt_access_denied');

        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile || $oProfile->id() == $this->_iProfileId)
            return _t('_sys_txt_access_denied');

        return BxDolConnection::getObjectInstance($sObjConnection)->checkAllowedConnect ($this->_iProfileId, $oProfile->id(), $isPerformAction, $isMutual, $isInvertResult, $isSwap);
    }

    protected function _buildRssParams($sMode, $aArgs)
    {
        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'connections':
                $aParams = array(
                    'object' => isset($aArgs[0]) ? $aArgs[0] : '',
                    'type' => isset($aArgs[1]) ? $aArgs[1] : '',
                    'profile' => isset($aArgs[2]) ? (int)$aArgs[2] : 0,
                    'mutual' => isset($aArgs[3]) ? (int)$aArgs[3] : 0,
                    'profile2' => isset($aArgs[4]) ? (int)$aArgs[4] : 0,
                );
                break;
        }

        return $aParams;
    }

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

            $aUnitParams = array();
            if($bProfile && is_array($mixedProfile['info']))
                $aUnitParams = array('template' => array('vars' => $mixedProfile['info']));

            $s .= $oProfile->getUnit(0, $aUnitParams);
        }

        // return profiles + paginate
        return $s . (!$iStart && $oPaginate->getNum() <= $iLimit ?  '' : $oPaginate->getSimplePaginate());
    }

    protected function _serviceGetTimelineProfileImage($aEvent, $aBrowseParams, $aBuildParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $aFileInfo = BxDolStorage::getObjectInstance($CNF[$aBuildParams['stg']])->getFile((int)$aEvent['object_id']);
        if(empty($aFileInfo) || !is_array($aFileInfo))
            return false;
            
        $aEventContent = unserialize($aEvent['content']);
        if(!is_array($aEventContent) || empty($aEventContent['content']))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($aEventContent['content']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        $oProfile = BxDolProfile::getInstanceMagic($aContentInfo['profile_id']);
        if (!$oProfile->isActive())
            return false;
            
        $sUserName = $oProfile->getDisplayName();

        $sSample = isset($CNF['T'][$aBuildParams['txt_sswa']]) ? $CNF['T'][$aBuildParams['txt_sswa']] : $CNF['T'][$aBuildParams['txt_ss']];

        //--- Title & Description
        $sTitle = !empty($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : '';
        if(empty($sTitle) && !empty($aContentInfo[$CNF['FIELD_TEXT']]))
            $sTitle = $aContentInfo[$CNF['FIELD_TEXT']];

        $sDescription = _t($CNF['T'][$aBuildParams['txt_sau']], $sUserName, _t($sSample));

        return array(
            'owner_id' => $aEvent['owner_id'],
            'object_owner_id' => $aContentInfo['profile_id'],
            'icon' => !empty($CNF['ICON']) ? $CNF['ICON'] : '',
            'sample' => $sSample,
            'sample_wo_article' => $CNF['T'][$aBuildParams['txt_ss']],
            'sample_action' => isset($CNF['T'][$aBuildParams['txt_sa']]) ? $CNF['T'][$aBuildParams['txt_sa']] : '',
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]),
            'content' => $this->_getContentForTimelineProfileImage($aEvent, $aBrowseParams, $aBuildParams, $aContentInfo, $aFileInfo), //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'views' => '',
            'votes' => '',
            'scores' => '',
            'reports' => '',
            'comments' => '',
            'title' => $sTitle, //may be empty.
            'description' => $sDescription //may be empty.
        );
    }

    protected function _serviceGetTimelineProfileImageAllowedView($aEvent)
    {
        $sError = _t('_sys_access_denied_to_private_content');

        if(empty($aEvent['content']) || !is_array($aEvent['content']) || empty($aEvent['content']['id']))
            return $sError;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['content']['id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return $sError;

        return $this->serviceCheckAllowedViewForProfile($aContentInfo);
    }

    protected function _getContentForTimelineProfileImage($aEvent, $aBrowseParams, $aBuildParams, $aContentInfo, $aFileInfo)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

    	//--- Image(s)
        $sImage = $this->_oConfig->getImageUrl($aFileInfo['id'], $aBuildParams['trans']);
        $sImageOrig = $this->_oConfig->getImageUrl($aFileInfo['id'], $aBuildParams['trans_orig']);
        if(!empty($sImage)) {
            if(empty($sImageOrig))
                $sImageOrig = $sImage;

            $a = array('url' => $sUrl, 'src' => $sImage);
            if (CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedView($aContentInfo))
                $a['src_orig'] = $sImageOrig;
            $aImages = array($a);
        }

    	return array(
            'sample' => isset($CNF['T'][$aBuildParams['txt_sswa']]) ? $CNF['T'][$aBuildParams['txt_sswa']] : $CNF['T'][$aBuildParams['txt_ss']],
            'sample_wo_article' => $CNF['T'][$aBuildParams['txt_ss']],
            'sample_action' => isset($CNF['T'][$aBuildParams['txt_sa']]) ? $CNF['T'][$aBuildParams['txt_sa']] : '',
            'id' => $aContentInfo[$CNF['FIELD_ID']],
            'url' => $sUrl,
            'title' =>  '',
            'text' => '',
            'images' => $aImages,
            'videos' => array()
        );
    }

    protected function _entityComments($sObject, $iId = 0)
    {
        if(!$iId)
            $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iId)
            $iId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        if(!$iId)
            return false;

        $oCmts = BxDolCmts::getObjectInstance($sObject, $iId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts->getCommentsBlock(array(), array('in_designbox' => false));
    }

    protected function _getContent($iContentId = 0, $sFuncGetContent = 'getContentInfoById')
    {
        if(!$iContentId && bx_get('id') === false && bx_get('profile_id') !== false) {
            $oProfile = BxDolProfile::getInstance((int)bx_get('profile_id'));
            if($oProfile)
                $iContentId = $oProfile->getContentId();
        }

        return parent::_getContent($iContentId, $sFuncGetContent);
    }

    /** Returns list of members by mode with limited number of records for React Jot
     * @param string $sMode
     * @param int $iStart
     * @param int $iPerPage
     * @return mixed
     */

    public function serviceGetMembers($sMode = 'active', $iStart = 0, $iPerPage = 10){
        bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass($sMode);

        $o -> aCurrent['paginate'] = array('perPage' => $iPerPage, 'forceStart' => $iStart);
        return $o -> getSearchData();
    }
}

/** @} */
