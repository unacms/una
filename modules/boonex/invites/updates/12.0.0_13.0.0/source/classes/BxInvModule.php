<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolAcl');

define('BX_INV_TYPE_FROM_MEMBER', 'from_member');
define('BX_INV_TYPE_FROM_SYSTEM', 'from_system');

class BxInvModule extends BxDolModule
{
    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * ACTION METHODS
     */
    function actionGetLink()
    {
        $iProfileId = $this->getProfileId();
        $iAccountId = $this->getAccountId($iProfileId);

        $mixedAllowed = $this->isAllowedInvite($iProfileId);
        if($mixedAllowed !== true)
            return echoJson(array('message' => $mixedAllowed));

        if(!isAdmin($iAccountId)) {
            if($this->_oConfig->getCountPerUser() <= 0)
                return echoJson(array('message' => _t('_bx_invites_err_limit_reached')));
        }

        $oKeys = BxDolKey::getInstance();
        if(!$oKeys)
            return  echoJson(array('message' => _t('_bx_invites_err_not_available')));

        $sKey = $oKeys->getNewKey(false, $this->_oConfig->getKeyLifetime());

        $oForm = $this->getFormObjectInvite();
        $oForm->insert(array(
            'account_id' => $iAccountId,
            'profile_id' => $iProfileId,
            'key' => $sKey,
            'email' => '',
            'date' => time()
        ));
        $this->onInvite($iProfileId);

        echoJson(array('popup' => $this->_oTemplate->getLinkPopup(
            $this->getJoinLink($sKey)
        )));
    }
    
    public function actionSetSeenMark($Code)
    {
        header('Content-Type: image/png');
        if (isset($Code) && trim($Code) != "")
            $this->_oDb->updateDateSeenForInvite($Code);
    }

    /**
     * SERVICE METHODS
     */
    
    public function serviceGetSafeServices()
    {
        return array (
            'GetBlockInvite' => '',
            'GetBlockFormInvite' => '',
            'GetBlockFormRequest' => '',
        );
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-other Other
     * @subsubsection bx_invites-get_include get_include
     * 
     * @code bx_srv('bx_invites', 'get_include', [...]); @endcode
     * 
     * Get all necessary CSS and JS files to include in a page.
     *
     * @return string with all necessary CSS and JS files.
     * 
     * @see BxInvModule::serviceGetInclude
     */
    /** 
     * @ref bx_invites-get_include "get_include"
     */
    public function serviceGetInclude()
    {
        return $this->_oTemplate->getInclude();
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-page_blocks Page Blocks
     * @subsubsection bx_invites-get_block_invite get_block_invite
     * 
     * @code bx_srv('bx_invites', 'get_block_invite', [...]); @endcode
     * 
     * Get page block for member's Dashboard which displays invitations related info and action(s).
     *
     * @return an array describing a block to display on the site or empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxInvModule::serviceGetBlockInvite
     */
    /** 
     * @ref bx_invites-get_block_invite "get_block_invite"
     */
    public function serviceGetBlockInvite()
    {
        $iProfileId = $this->getProfileId();
        $iAccountId = $this->getAccountId($iProfileId);

        $mixedAllowed = $this->isAllowedInvite($iProfileId);
        if($mixedAllowed !== true)
            return '';

        if(!isAdmin($iAccountId) && $this->_oConfig->getCountPerUser() <= 0)
            return '';

        return array(
            'content' => $this->_oTemplate->getBlockInvite($iAccountId, $iProfileId)
        );
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-page_blocks Page Blocks
     * @subsubsection bx_invites-get_block_form_invite get_block_form_invite
     * 
     * @code bx_srv('bx_invites', 'get_block_form_invite', [...]); @endcode
     * 
     * Get page block with invite form.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxInvModule::serviceGetBlockFormInvite
     */
    /** 
     * @ref bx_invites-get_block_form_invite "get_block_form_invite"
     */
    public function serviceGetBlockFormInvite()
    {
        $oForm = $this->getFormObjectInvite();
        $oForm->aInputs['text']['value'] = _t('_bx_invites_msg_invitation');

        $sResult = '';
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            
            $sResult = $this->processFormObjectInvite($oForm);
            $sResult = MsgBox($sResult);
        }

        return array(
            'content' => $sResult . $oForm->getCode()
        );
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-page_blocks Page Blocks
     * @subsubsection bx_invites-get_block_form_request get_block_form_request
     * 
     * @code bx_srv('bx_invites', 'get_block_form_request', [...]); @endcode
     * 
     * Get page block with request invitation form.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxInvModule::serviceGetBlockFormRequest
     */
    /** 
     * @ref bx_invites-get_block_form_request "get_block_form_request"
     */
    public function serviceGetBlockFormRequest()
    {
        $mixedResult = $this->_oTemplate->getBlockFormRequest();
        if(is_array($mixedResult)) {
            echoJson($mixedResult);
            exit;
        }

        return array(
            'content' => $mixedResult
        );
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-page_blocks Page Blocks
     * @subsubsection bx_invites-get_block_manage_requests get_block_manage_requests
     * 
     * @code bx_srv('bx_invites', 'get_block_manage_requests', [...]); @endcode
     * 
     * Get page block with manage invitation requests table.
     *
     * @return HTML string with block content to display on the site or empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxInvModule::serviceGetBlockManageRequests
     */
    /** 
     * @ref bx_invites-get_block_manage_requests "get_block_manage_requests"
     */
    public function serviceGetBlockManageRequests()
    {
        return $this->getBlockManage('requests');
    }
    
    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-page_blocks Page Blocks
     * @subsubsection bx_invites-get_block_manage_invites get_block_manage_requests
     * 
     * @code bx_srv('bx_invites', 'get_block_manage_invites', [...]); @endcode
     * 
     * Get page block with manage invitations table.
     *
     * @return HTML string with block content to display on the site or empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxInvModule::serviceGetBlockManageInvites
     */
    /** 
     * @ref bx_invites-get_block_manage_invites "get_block_manage_invites"
     */
    public function serviceGetBlockManageInvites()
    {
        return $this->getBlockManage('invites');
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-other Other
     * @subsubsection bx_invites-get_menu_addon_requests get_menu_addon_requests
     * 
     * @code bx_srv('bx_invites', 'get_menu_addon_requests', [...]); @endcode
     * 
     * Get number or invitation requests.
     *
     * @return integer value with number of invitation requests.
     * 
     * @see BxInvModule::serviceGetMenuAddonRequests
     */
    /** 
     * @ref bx_invites-get_menu_addon_requests "get_menu_addon_requests"
     */
    public function serviceGetMenuAddonRequests()
    {
        return array('counter3_value' => $this->_oDb->getRequests(array('type' => 'count_all')));
    }

    /**
     * @page service Service Calls
     * @section bx_invites Invitations
     * @subsection bx_invites-other Other
     * @subsubsection bx_invites-account_add_form_check account_add_form_check
     * 
     * @code bx_srv('bx_invites', 'account_add_form_check', [...]); @endcode
     * 
     * Perform neccessary checking on join form.
     *
     * @return empty string - if join is allowed and should be processed as usual, non-empty string - if join form need to be replaced with this code.
     * 
     * @see BxInvModule::serviceAccountAddFormCheck
     */
    /** 
     * @ref bx_invites-account_add_form_check "account_add_form_check"
     */
    public function serviceAccountAddFormCheck()
    {
        $sReturn = '';

        $oSession = BxDolSession::getInstance();
        $sKeyCode = $this->_oConfig->getKeyCode();

        $bKey = bx_get($sKeyCode) !== false;
        if($bKey) {
            $sKey = bx_process_input(bx_get($sKeyCode));

            $oKeys = BxDolKey::getInstance();
            if($oKeys && $oKeys->isKeyExists($sKey))
                $oSession->setValue($sKeyCode, $sKey);
        }

        $sKey = $oSession->getValue($sKeyCode);
        if($sKey !== false)
            return $sReturn;

        if($bKey)
            $sReturn .= MsgBox(_t('_bx_invites_err_used'));
        
        if ($this->_oConfig->isRegistrationByInvitation())
            $sReturn .= $this->_oTemplate->getBlockRequest();

        return $sReturn;
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

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'request_notify', 'module_name' => $sModule, 'module_method' => 'get_notifications_request', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            ),
            'settings' => array(
                array('group' => 'request', 'unit' => $sModule, 'action' => 'request_notify', 'types' => array('personal')),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'request_notify'),
            )
        );
    }
    
    /**
     * Entry post for Notifications module
     */
    public function serviceGetNotificationsRequest($aEvent)
    {  
        if (getParam('bx_invites_requests_notifications') != 'on')
             return array();
        
        $aRequest = $this->_oDb->getRequests(array('type' => 'by_id', 'value' => $aEvent['object_id']));
        if (!$aRequest)
            return array();
           
        $CNF = &$this->_oConfig->CNF;
        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_REQUESTS']);
            
		return array(
			'entry_sample' => $aRequest['name'] . '(' . $aRequest['email'] . ')',
			'entry_url' => $sEntryUrl,
			'entry_caption' => $aRequest['text'],
			'entry_author' => '',
			'entry_privacy' => '', //may be empty or not specified. In this case Public privacy will be used.
			'lang_key' => '_bx_invites_alert_action_request', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }
    
    
    public function attachAccountIdToInvite($iAccountId, $sKey)
    {
        $this->_oDb->attachAccountIdToInvite($iAccountId, $sKey);
    }
    
    public function invite($sType, $sEmails, $sText, $mixedLimit = false, $oForm = null)
    {
        $iProfileId = $this->getProfileId();
        $iAccountId = $this->getAccountId($iProfileId);

        $oKeys = BxDolKey::getInstance();
        if(!$oKeys || !in_array($sType, array(BX_INV_TYPE_FROM_MEMBER, BX_INV_TYPE_FROM_SYSTEM)))
            return false;

        $iKeyLifetime = $this->_oConfig->getKeyLifetime();

        $sEmailTemplate = '';
        switch($sType) {
            case BX_INV_TYPE_FROM_MEMBER:
                $sEmailTemplate = 'bx_invites_invite_form_message';
                break;

            case BX_INV_TYPE_FROM_SYSTEM:
                $sEmailTemplate = 'bx_invites_invite_by_request_message';
                break;
        }

        if(empty($oForm))
            $oForm = $this->getFormObjectInvite();

        $aMessage = BxDolEmailTemplates::getInstance()->parseTemplate($sEmailTemplate, array(
            'text' => $sText
        ), $iAccountId, $iProfileId);

        $aAccountIds = array();
        $iDate = time();
        $aEmails = preg_split("/[\s\n,;]+/", $sEmails);
        if(is_array($aEmails) && !empty($aEmails)){
            foreach($aEmails as $sEmail) {
                if($mixedLimit !== false && (int)$mixedLimit <= 0)
                    break;

                $sEmail = trim($sEmail);
                if(empty($sEmail))
                    continue;

                $sKey = $oKeys->getNewKey(false, $iKeyLifetime);
                if(sendMail($sEmail, $aMessage['Subject'], $aMessage['Body'], 0, array('join_url' => $this->getJoinLink($sKey), 'seen_image_url' => $this->getSeenImageUrl($sKey)), BX_EMAIL_SYSTEM)) {
                    $iInviteId = (int)$this->_oDb->insertInvite($iAccountId, $iProfileId, $sKey, $sEmail, $iDate);
                    array_push($aAccountIds, $iInviteId);
                    
                    $this->onInvite($iProfileId);
                    
                    if($mixedLimit !== false)
                        $mixedLimit -= 1;
                }
            }
        }
        return $aAccountIds;
    }
    
    public function processFormObjectInvite($oForm)
    {
        $iProfileId = $this->getProfileId();
        $iAccountId = $this->getAccountId($iProfileId);

        $mixedAllowed = $this->isAllowedInvite($iProfileId);
        if($mixedAllowed !== true)
            return $mixedAllowed;

        $mixedInvites = false;
        if(!isAdmin($iAccountId)) {
            if( $this->_oConfig->getCountPerUser() <= 0)
                return _t('_bx_invites_err_limit_reached');
        }
        
        $sEmails = bx_process_input($oForm->getCleanValue('emails'));
        $sText = bx_process_pass($oForm->getCleanValue('text'));
        $mixedResult = $this->invite(BX_INV_TYPE_FROM_MEMBER, $sEmails, $sText, $mixedInvites, $oForm);
        $this->onInvite($iProfileId);
        if($mixedResult !== false)
            $sResult = _t('_bx_invites_msg_invitation_sent', count($mixedResult));
        else
            $sResult = _t('_bx_invites_err_not_available');
        
        return  $sResult;
    }

    public function isAllowedInvite($iProfileId, $bPerform = false)
    {
        $aCheckResult = checkActionModule($iProfileId, 'invite', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedRequest($iProfileId, $bPerform = false)
    {
        $aCheckResult = checkActionModule($iProfileId, 'request', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedDeleteRequest($iProfileId, $bPerform = false)
    {
        $aCheckResult = checkActionModule($iProfileId, 'delete request', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }
    
    public function isAllowedDeleteInvite($iProfileId, $bPerform = false)
    {
        $aCheckResult = checkActionModule($iProfileId, 'delete invite', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function getProfileId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getProfileObject($iProfileId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return $oProfile;
    }

    public function getAccountId($iProfileId)
    {
        $oProfile = $this->getProfileObject($iProfileId);
        if($oProfile->id() == 0)
            return 0;

        return $oProfile->getAccountId();
    }

    public function onRequest($iRequestId)
    {
        //--- Event -> Request for Alerts Engine ---//
        bx_alert($this->_oConfig->getObject('alert'), 'request', $iRequestId);
        $aProfiles = BxDolAclQuery::getInstance()->getProfilesByMembership(array(MEMBERSHIP_ID_MODERATOR, MEMBERSHIP_ID_ADMINISTRATOR));
        foreach($aProfiles as $aProfile) {
            bx_alert($this->_oConfig->getObject('alert'), 'request_notify', $iRequestId, 0, array('parent_author_id' => $aProfile['id']));
        }
        //--- Event -> Request for Alerts Engine ---//
    }

    public function getFormObjectInvite($sDisplay = '')
    {
        if(empty($sDisplay))
            $sDisplay = $this->_oConfig->getObject('form_display_invite_send');

        bx_import('FormCheckerHelper', $this->_aModule);
        return BxDolForm::getObjectInstance($this->_oConfig->getObject('form_invite'), $sDisplay);
    }
    
    protected function getBlockManage($sType)
    {
        $this->_oTemplate->addJs('jquery.form.min.js');
        $this->_oTemplate->addJs('main.js');
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_' . $sType));
        if(!$oGrid)
            return '';

        $this->_oTemplate->addCss(array('main.css'));
        $this->_oTemplate->addJsTranslation(array('_sys_grid_search'));
       
        return array(
           'menu' => $this->_oTemplate->getMenuForManageBlocks($sType),
           'content' => $this->_oTemplate->getJsCode('main', array('grid' => $sType)) . $oGrid->getCode()
       );
    }

    protected function onInvite($iProfileId)
    {
        $this->isAllowedInvite($iProfileId, true);

        //--- Event -> Invite for Alerts Engine ---//
        bx_alert($this->_oConfig->getObject('alert'), 'invite', 0, $iProfileId);
        //--- Event -> Invite for Alerts Engine ---//
    }
    
    protected function getJoinLink($sKey)
    {
        $sKeyCode = $this->_oConfig->getKeyCode();

        $sJoinUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account');
        return bx_append_url_params($sJoinUrl, array($sKeyCode => $sKey));
    }
    
    protected function getSeenImageUrl($sKey)
    {
        return  BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'SetSeenMark/' . $sKey . "/";
    }

}

/** @} */
