<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_BASE_MOD_GROUPS_MMODE_MULTI_ROLES', 'multi_roles');
define('BX_BASE_MOD_GROUPS_MMODE_PAID_JOIN', 'paid_join');

define('BX_BASE_MOD_GROUPS_ROLE_COMMON', 0);
define('BX_BASE_MOD_GROUPS_ROLE_ADMINISTRATOR', 1);
define('BX_BASE_MOD_GROUPS_ROLE_MODERATOR', 2);

define('BX_BASE_MOD_GROUPS_ACTION_EDIT', 'edit');
define('BX_BASE_MOD_GROUPS_ACTION_CHANGE_COVER', 'change_cover');
define('BX_BASE_MOD_GROUPS_ACTION_INVITE', 'invite');
define('BX_BASE_MOD_GROUPS_ACTION_MANAGE_FANS', 'manage_fans');
define('BX_BASE_MOD_GROUPS_ACTION_MANAGE_ROLES', 'manage_roles');
define('BX_BASE_MOD_GROUPS_ACTION_DELETE', 'delete');

define('BX_BASE_MOD_GROUPS_PERIOD_UNIT_DAY', 'day');
define('BX_BASE_MOD_GROUPS_PERIOD_UNIT_WEEK', 'week');
define('BX_BASE_MOD_GROUPS_PERIOD_UNIT_MONTH', 'month');
define('BX_BASE_MOD_GROUPS_PERIOD_UNIT_YEAR', 'year');

/**
 * Groups profiles module.
 */
class BxBaseModGroupsModule extends BxBaseModProfileModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetInitialMembers ()
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }
    
    /**
     * Process Process Invitation
     */
    public function actionProcessInvite ($sKey, $iGroupProfileId, $bAccept)
    {
        $aData = $this->_oDb->getInviteByKey($sKey, $iGroupProfileId);
        if (isset($aData['invited_profile_id'])){
            $CNF = &$this->_oConfig->CNF;
            if (!isset($CNF['OBJECT_CONNECTIONS']) || !($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
                return '';
            $iInvitedProfileId = $aData['invited_profile_id'];
            if ($iInvitedProfileId != bx_get_logged_profile_id())
                return '';
            if ($bAccept){
                if($oConnection && !$oConnection->isConnected($iInvitedProfileId, $iGroupProfileId)){
                    $oConnection->addConnection($iInvitedProfileId, $iGroupProfileId);
                    $oConnection->addConnection($iGroupProfileId, $iInvitedProfileId);
                }
            }
            $this->_oDb->deleteInviteByKey($sKey, $iGroupProfileId);
        }   
    }

    public function serviceGetOptionsMembersMode()
    {
        $CNF = &$this->_oConfig->CNF;

        return array(
            array('key' => '', 'value' => _t('_None')),
            array('key' => BX_BASE_MOD_GROUPS_MMODE_MULTI_ROLES, 'value' => _t($CNF['T']['option_members_mode_' . BX_BASE_MOD_GROUPS_MMODE_MULTI_ROLES])),
        );
    }

    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit.html';

        return parent::serviceGetSearchResultUnit($iContentId, $sUnitTemplate);
    }

    /**
     * @see BxBaseModProfileModule::serviceGetSpaceTitle
     */ 
    public function serviceGetSpaceTitle()
    {
        return _t($this->_oConfig->CNF['T']['txt_sample_single']);
    }
    
    /**
     * @see iBxDolProfileService::serviceGetParticipatingProfiles
     */ 
    public function serviceGetParticipatingProfiles($iProfileId, $aConnectionObjects = false)
    {
        if (isset($this->_oConfig->CNF['OBJECT_CONNECTIONS'])){
            $aConnectionObjects = array($this->_oConfig->CNF['OBJECT_CONNECTIONS'], 'sys_profiles_subscriptions');
            return parent::serviceGetParticipatingProfiles($iProfileId, $aConnectionObjects);
        }
        return parent::serviceGetParticipatingProfiles($iProfileId);
    }
    
    /**
     * Check if this module entry can be used as profile
     */
    public function serviceActAsProfile ()
    {
        return false;
    }

    /**
     * Check if this module is group profile
     */
    public function serviceIsGroupProfile ()
    {
        return true;
    }

    public function serviceIsEnableForContext($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $sCnfKey = 'ENABLE_FOR_CONTEXT_IN_MODULES';
        if(empty($iProfileId) || empty($CNF[$sCnfKey]) || !is_array($CNF[$sCnfKey]))
            return false;

        if(in_array(BxDolProfile::getInstance($iProfileId)->getModule(), $CNF[$sCnfKey]))
            return true;

        return false;
    }

    /**
     * check if provided profile is member of the group 
     */ 
    public function serviceIsFan ($iGroupProfileId, $iProfileId = false) 
    {
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        return $this->isFan($oGroupProfile->getContentId(), $iProfileId);
    }

    /**
     * check if provided profile is admin of the group 
     */ 
    public function serviceIsAdmin ($iGroupProfileId, $iProfileId = false) 
    {
        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);

        $iGroupContentId = $oGroupProfile->getContentId();
        if(!$this->isFan($iGroupContentId, $iProfileId))
            return false;

        $aGroupContentInfo = $this->_oDb->getContentInfoById($iGroupContentId);
        return $this->_oDb->isAdmin($iGroupProfileId, $iProfileId, $aGroupContentInfo);
    }

    public function serviceGetAdminRole($iGroupProfileId, $iProfileId = false)
    {
        if(!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();

        if(!$this->serviceIsAdmin($iGroupProfileId, $iProfileId))
            return 0;

        return $this->_oDb->getRole($iGroupProfileId, $iProfileId);
    }

    /**
     * Delete profile from fans and admins tables
     * @param $iProfileId profile id 
     */
    public function serviceDeleteProfileFromFansAndAdmins ($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        $this->_oDb->deleteAdminsByProfileId($iProfileId);

        if (isset($CNF['OBJECT_CONNECTIONS']) && ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            $oConnection->onDeleteInitiatorAndContent($iProfileId);
    }

    /**
     * Reset group's author for particular group
     * @param $iContentId group id 
     * @return false of error, or number of updated records on success
     */
    public function serviceReassignEntityAuthor ($iContentId)
    {
        $aContentInfo = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aContentInfo)
            return false;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName())))
            return false;

        $aAdmins = $this->_oDb->getAdmins($oGroupProfile->id());

        return $this->_oDb->updateAuthorById($iContentId, $aAdmins ? array_pop($aAdmins) : 0);
    }

    /**
     * Entry actions and social sharing block
     */
    public function serviceEntityAllActions ($mixedContent = false, $aParams = array())
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

        if(!empty($CNF['FIELD_PICTURE']) && !empty($aContentInfo[$CNF['FIELD_PICTURE']]))
            $aParams = array_merge(array(
                'entry_thumb' => (int)$aContentInfo[$CNF['FIELD_PICTURE']]
            ), $aParams); 

        return parent::serviceEntityAllActions ($mixedContent, $aParams);
    }
    
    /**
     * Reset group's author when author profile is deleted
     * @param $iProfileId profile id 
     * @return number of changed items
     */
    public function serviceReassignEntitiesByAuthor ($iProfileId)
    {
        $a = $this->_oDb->getEntriesByAuthor((int)$iProfileId);
        if (!$a)
            return 0;

        $iCount = 0;
        foreach ($a as $aContentInfo)
            $iCount += ('' == $this->serviceReassignEntityAuthor($aContentInfo[$this->_oConfig->CNF['FIELD_ID']]) ? 1 : 0);

        return $iCount;
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aFieldsProfile[$CNF['FIELD_NAME']] = $aFieldsProfile['name'];
        $aFieldsProfile[$CNF['FIELD_TEXT']] = isset($aFieldsProfile['description']) ? $aFieldsProfile['description'] : '';
        unset($aFieldsProfile['name']);
        unset($aFieldsProfile['description']);
        return $aFieldsProfile;
    }

    public function serviceOnRemoveConnection ($iGroupProfileId, $iInitiatorId)
    {
        $CNF = &$this->_oConfig->CNF;

        list ($iProfileId, $iGroupProfileId, $oGroupProfile) = $this->_prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId);
        if (!$oGroupProfile)
            return false;

        $this->_oDb->fromAdmins($iGroupProfileId, $iProfileId);

        if ($oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions'))
            return $oConn->removeConnection($iProfileId, $iGroupProfileId);

        return false;
    }

    public function serviceAddMutualConnection ($iGroupProfileId, $iInitiatorId, $bSendInviteOnly = false)
    {        
        $CNF = &$this->_oConfig->CNF;

        list ($iProfileId, $iGroupProfileId, $oGroupProfile) = $this->_prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId);
        if (!$oGroupProfile)
            return false;

        if (!($aContentInfo = $this->_oDb->getContentInfoById((int)BxDolProfile::getInstance($iGroupProfileId)->getContentId())))
            return false;

        if (!isset($CNF['OBJECT_CONNECTIONS']) || !($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            return false;

        $sEntryTitle = $aContentInfo[$CNF['FIELD_NAME']];
        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

        // send invitation to the group 
        $sModule = $this->getName();
        if ($bSendInviteOnly && !$oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id()) && !$oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId) && bx_get_logged_profile_id() != $iProfileId) {
            bx_alert($sModule, 'join_invitation', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
                'content' => $aContentInfo, 
                'entry_title' => $sEntryTitle, 
                'entry_url' => $sEntryUrl, 
                'group_profile' => $iGroupProfileId, 
                'profile' => $iProfileId, 
                'notification_subobject_id' => $iProfileId, 
                'object_author_id' => $iGroupProfileId
            ));

            /**
             * 'Invitation Received' alert for Notifications module.
             * Note. It's essential to use Recipient ($iInitiatorId) in 'object_author_id' parameter. 
             * In this case notification will be received by Recipient profile.
             */
            bx_alert($sModule, 'join_invitation_notif', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
                'object_author_id' => $iInitiatorId, 
                'privacy_view' => isset($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) ? $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']] : 3, 
            ));
        }
        // send notification to group's admins that new connection is pending confirmation 
        elseif (!$bSendInviteOnly && $oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id()) && !$oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId) && $aContentInfo['join_confirmation']) {

            bx_alert($this->getName(), 'join_request', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
            	'object_author_id' => $iGroupProfileId,
            	'performer_id' => $iProfileId, 

            	'content' => $aContentInfo, 
            	'entry_title' => $sEntryTitle, 
            	'entry_url' => $sEntryUrl, 

            	'group_profile' => $iGroupProfileId, 
            	'profile' => $iProfileId
            ));
        }
        // send notification that join request was accepted 
        else if (!$bSendInviteOnly && $oConnection->isConnected((int)$iInitiatorId, $oGroupProfile->id(), true) && $oGroupProfile->getModule() != $this->getName() && bx_get_logged_profile_id() != $iProfileId) {
            bx_alert($this->getName(), 'join_request_accepted', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
            	'object_author_id' => $iGroupProfileId,
            	'performer_id' => $iProfileId,

            	'content' => $aContentInfo, 
            	'entry_title' => $sEntryTitle, 
            	'entry_url' => $sEntryUrl, 

            	'group_profile' => $iGroupProfileId, 
            	'profile' => $iProfileId
            ));
        }

        // new fan was added
        if ($oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId, true)) {
            // follow group on join
            if (BxDolService::call($oGroupProfile->getModule(), 'act_as_profile')){
                 $this->addFollower($oGroupProfile->id(), (int)$iInitiatorId);
            }
            else{
                 $this->addFollower((int)$iInitiatorId, $oGroupProfile->id()); 
            }
            
            bx_alert($this->getName(), 'fan_added', $aContentInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
            	'object_author_id' => $iGroupProfileId,
            	'performer_id' => $iProfileId,

            	'content' => $aContentInfo,
            	'entry_title' => $sEntryTitle, 
            	'entry_url' => $sEntryUrl,

            	'group_profile' => $iGroupProfileId, 
            	'profile' => $iProfileId,
            ));
            
            $this->doAudit($iGroupProfileId, $iInitiatorId, '_sys_audit_action_group_join_request_accepted');
            
            return false;
        }

        // don't automatically add connection (mutual) if group requires manual join confirmation
        if ($bSendInviteOnly || $aContentInfo['join_confirmation'])
            return false;

        // check if connection already exists
        if ($oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId, true) || $oConnection->isConnected($oGroupProfile->id(), (int)$iInitiatorId))
            return false;

        if (!$oConnection->addConnection($oGroupProfile->id(), (int)$iInitiatorId))
            return false;

        return true;
    }

    public function serviceFansTable ()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID_CONNECTIONS']);
        if (!$oGrid)
            return false;

        return $oGrid->getCode();
    }
    
	public function serviceInvitesTable ()
    {
		if (!isset($this->_oConfig->CNF['OBJECT_GRID_INVITES']))
			return false;
		
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->CNF['OBJECT_GRID_INVITES']);
        if (!$oGrid)
            return false;

        return $oGrid->getCode();
    }
	
    public function serviceFans ($iContentId = 0, $bAsArray = false)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName())))
            return false;

        if(!$bAsArray) {
            bx_import('BxDolConnection');
            $mixedResult = $this->serviceBrowseConnectionsQuick ($oGroupProfile->id(), $this->_oConfig->CNF['OBJECT_CONNECTIONS'], BX_CONNECTIONS_CONTENT_TYPE_CONTENT, true);
            if (!$mixedResult)
                return MsgBox(_t('_sys_txt_empty'));
        }
        else
            $mixedResult = BxDolConnection::getObjectInstance($this->_oConfig->CNF['OBJECT_CONNECTIONS'])->getConnectedContent($oGroupProfile->id(), true);

        return $mixedResult;
    }
    
    public function serviceFansWithoutAdmins ($iContentId = 0, $bAsArray = false)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName())))
            return false;
        
        $CNF = &$this->_oConfig->CNF;

        $aFans = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])->getConnectedContent($oGroupProfile->id(), true);
        if(empty($aFans) || !is_array($aFans))
            return false;

        $aAdmins = $this->_oDb->getAdmins($oGroupProfile->id());
        if(!empty($aAdmins) && is_array($aAdmins))
            $aFans = array_diff($aFans, $aAdmins);

        $iStart = (int)bx_get('start');
        $iLimit = !empty($CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? getParam($CNF['PARAM_NUM_CONNECTIONS_QUICK']) : 4;
        if(!$iLimit)
            $iLimit = 4;

        return $this->_serviceBrowseQuick($aFans, $iStart, $iLimit);
    }

    public function serviceAdmins ($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iContentId)
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());
        if(!$oGroupProfile)
            return false;

        $iStart = (int)bx_get('start');
        $iLimit = !empty($CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? getParam($CNF['PARAM_NUM_CONNECTIONS_QUICK']) : 4;
        if(!$iLimit)
            $iLimit = 4;
        
        $aProfiles = $this->_oDb->getAdmins($oGroupProfile->id(), $iStart,  $iLimit+1);
        if(empty($aProfiles) || !is_array($aProfiles))
            return false;

        return $this->_serviceBrowseQuick($aProfiles, $iStart, $iLimit);
    }

    public function serviceBrowseJoinedEntries ($iProfileId = 0, $bDisplayEmptyMsg = false)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';

        return $this->_serviceBrowse ('joined_entries', array('joined_profile' => $iProfileId), BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }

    public function serviceBrowseCreatedEntries ($iProfileId = 0, $bDisplayEmptyMsg = false)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';

        return $this->_serviceBrowse ('created_entries', array('author' => $iProfileId), BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }
    
    public function serviceEntityPricing($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';

        if(!$this->_oConfig->isPaidJoin())
            return '';

        if($this->checkAllowedManageAdmins($iProfileId) !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox(_t('_Access denied'));

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_PRICES_MANAGE']);
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }

    public function serviceEntityJoin($iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';

        if(!$this->_oConfig->isPaidJoin())
            return '';

        $oGrid = BxDolGrid::getObjectInstance($CNF['OBJECT_GRID_PRICES_VIEW']);
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }
    
    public function serviceEntityInvite ($iContentId = 0, $bErrorMsg = true)
    {
        if (isset($this->_oConfig->CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE']))
            return $this->_serviceEntityForm ('editDataForm', $iContentId, $this->_oConfig->CNF['OBJECT_FORM_ENTRY_DISPLAY_INVITE'], false, $bErrorMsg);
        return false;
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

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if(!$oGroupProfile)
            return false;

        return parent::serviceEntitySocialSharing(array($iContentId, $aContentInfo), array(
            'title' => $oGroupProfile->getDisplayName()
        ));
    }

    public function serviceIsPricingAvaliable($iProfileId)
    {
        if(!$this->_oConfig->isPaidJoin())
            return false;

        if($this->checkAllowedManageAdmins($iProfileId) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return true;
    }

    public function serviceIsPaidJoinAvaliable($iProfileId)
    {
        return $this->isPaidJoinByProfile($iProfileId);
    }

    public function serviceIsPaidJoinAvaliableByContent($iContentId)
    {
        $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());
        if(!$oProfile)
            return false;

        return $this->isPaidJoinByProfile($oProfile->id());
    }

    public function serviceIsFreeJoinAvaliable($iProfileId)
    {
        return !$this->isPaidJoinByProfile($iProfileId);
    }

    public function serviceIsFreeJoinAvaliableByContent($iContentId)
    {
        $oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());
        if(!$oProfile)
            return false;

        return !$this->isPaidJoinByProfile($oProfile->id());
    }

    public function isPaidJoinByProfile($iProfileId)
    {
        if(!$this->_oConfig->isPaidJoin())
            return false;

        $aPrices = $this->_oDb->getPrices(array('type' => 'by_profile_id', 'profile_id' => $iProfileId));
        if(empty($aPrices) || !is_array($aPrices))
            return false;

        return true;
    }

    /**
     * Integration with Payments.
     */
    public function serviceGetPaymentData()
    {
        return $this->_aModule;
    }

    public function serviceGetCartItem($mixedItemId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$mixedItemId)
            return array();

        if(is_numeric($mixedItemId))
            $aItem = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => (int)$mixedItemId));
        else 
            $aItem = $this->_oDb->getPrices(array('type' => 'by_name', 'value' => $mixedItemId));

        if(empty($aItem) || !is_array($aItem))
            return array();

        if(!$this->isPaidJoinByProfile($aItem['profile_id']))
            return array();

        $oGroupProfile = BxDolProfile::getInstance($aItem['profile_id']);
        if(!$oGroupProfile)
            return array();

        $aGroupProfile = $this->_oDb->getContentInfoById($oGroupProfile->getContentId());
        
        $aRoles = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_ROLES']);

        return array (
            'id' => $aItem['id'],
            'author_id' => $aGroupProfile[$CNF['FIELD_AUTHOR']],
            'name' => $aItem['name'],
            'title' => _t($CNF['T']['txt_cart_item_title'], $oGroupProfile->getDisplayName(), $aRoles[$aItem['role_id']], $aItem['period'], $aItem['period_unit']),
            'description' => '',
            'url' => $oGroupProfile->getUrl(),
            'price_single' => $aItem['price'],
            'price_recurring' => $aItem['price'],
            'period_recurring' => $aItem['period'],
            'period_unit_recurring' => $aItem['period_unit'],
            'trial_recurring' => 0
        );
    }

    public function serviceGetCartItems($iSellerId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	if(empty($iSellerId))
    	    return array();

        $sModule = $this->getName();
        $aRoles = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_ROLES']);

        $aGroups = $this->_oDb->getEntriesBy(array('type' => 'author', 'author' => $iSellerId));

        $aResult = array();
        foreach($aGroups as $aGroup) {
            $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aGroup[$CNF['FIELD_ID']], $sModule);
            if(!$oGroupProfile)
                continue;

            $aPrices = $this->_oDb->getPrices(array('type' => 'by_profile_id', 'profile_id' => $oGroupProfile->id()));
            if(empty($aPrices) || !is_array($aPrices))
                continue;

            $sTitle = $oGroupProfile->getDisplayName();
            $sUrl = $oGroupProfile->getUrl();

            foreach($aPrices as $aPrice)
                $aResult[] = array(
                    'id' => $aPrice['id'],
                    'author_id' => $iSellerId,
                    'name' => $aPrice['name'],
                    'title' => _t($CNF['T']['txt_cart_item_title'], $sTitle, $aRoles[$aPrice['role_id']], $aPrice['period'], $aPrice['period_unit']),
                    'description' => '',
                    'url' => $sUrl,
                    'price_single' => $aPrice['price'],
                    'price_recurring' => $aPrice['price'],
                    'period_recurring' => $aPrice['period'],
                    'period_unit_recurring' => $aPrice['period_unit']
               );
        }

        return $aResult;
    }

    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder);
    }

    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder);
    }

    public function serviceReregisterCartItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder)
    {
        return $this->_serviceReregisterItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder);
    }

    public function serviceReregisterSubscriptionItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder)
    {
        return $this->_serviceReregisterItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder);
    }

    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder);
    }

    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder); 
    }

    public function serviceCancelSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
    	return true;
    }

    protected function _serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aItem = $this->serviceGetCartItem($iItemId);
        if(empty($aItem) || !is_array($aItem))
            return array();

        $aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));

        $mixedPeriod = false;
        if((int)$aItemInfo['period'] != 0)
            $mixedPeriod = array(
                'period' => (int)$aItemInfo['period'], 
                'period_unit' => $aItemInfo['period_unit'], 
                'period_reserve' => $CNF['PARAM_RECURRING_RESERVE']
            );

        if(!$this->setRole($aItemInfo['profile_id'], $iClientId, $aItemInfo['role_id'], $mixedPeriod, $sOrder))
            return array();

        return $aItem;
    }

    protected function _serviceReregisterItem($iClientId, $iSellerId, $iItemIdOld, $iItemIdNew, $sOrder)
    {
        $aItem = $this->serviceGetCartItem($iItemIdNew);
        if(empty($aItem) || !is_array($aItem))
            return array();

        $aItemInfoOld = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemIdOld));
        if(empty($aItemInfoOld) || !is_array($aItemInfoOld))
            return array();

        $aItemInfoNew = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemIdNew));
        if(empty($aItemInfoNew) || !is_array($aItemInfoNew))
            return array();

        if(!$this->unsetRole($aItemInfoOld['profile_id'], $iClientId))
            return array();
        
        $aResult = $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemIdNew, 1, $sOrder);
        if(empty($aResult) || !is_array($aResult))
            return array();

    	return $aItem;
    }

    protected function _serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder)
    {
        $aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));
        if(empty($aItemInfo) || !is_array($aItemInfo))
            return false;

        return $this->unsetRole($aItemInfo['profile_id'], $iClientId);
    }


    /**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

        $aSettingsTypes = array('follow_member', 'follow_context');
        if($this->serviceActAsProfile())
            $aSettingsTypes = array('personal', 'follow_member');

        return array(
            'handlers' => array(
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module'),
                array('group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'),
                
                array('group' => $sModule . '_score_up', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVoteUp', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_up', 'module_class' => 'Module'),

                array('group' => $sModule . '_score_down', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVoteDown', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_down', 'module_class' => 'Module'),

                array('group' => $sModule . '_fan_added', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'fan_added', 'module_name' => $sModule, 'module_method' => 'get_notifications_fan_added', 'module_class' => 'Module'),

                array('group' => $sModule . '_join_invitation', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'join_invitation_notif', 'module_name' => $sModule, 'module_method' => 'get_notifications_join_invitation', 'module_class' => 'Module'),
                
                array('group' => $sModule . '_join_request', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'join_request', 'module_name' => $sModule, 'module_method' => 'get_notifications_join_request', 'module_class' => 'Module', 'module_event_privacy' => $this->_oConfig->CNF['OBJECT_PRIVACY_VIEW_NOTIFICATION_EVENT']),
                
                array('group' => $sModule . '_timeline_post_common', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'timeline_post_common', 'module_name' => $sModule, 'module_method' => 'get_notifications_timeline_post_common', 'module_class' => 'Module'),
            ),
            'settings' => array(
                array('group' => 'vote', 'unit' => $sModule, 'action' => 'doVote', 'types' => $aSettingsTypes),

                array('group' => 'score_up', 'unit' => $sModule, 'action' => 'doVoteUp', 'types' => $aSettingsTypes),

                array('group' => 'score_down', 'unit' => $sModule, 'action' => 'doVoteDown', 'types' => $aSettingsTypes),
                
                array('group' => 'fan', 'unit' => $sModule, 'action' => 'fan_added', 'types' => $aSettingsTypes),

                array('group' => 'invite', 'unit' => $sModule, 'action' => 'join_invitation_notif', 'types' => array('personal')),

                array('group' => 'join', 'unit' => $sModule, 'action' => 'join_request', 'types' => $aSettingsTypes),

                array('group' => 'timeline_post', 'unit' => $sModule, 'action' => 'timeline_post_common', 'types' => $aSettingsTypes)
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'doVote'),
                array('unit' => $sModule, 'action' => 'undoVote'),

                array('unit' => $sModule, 'action' => 'doVoteUp'),
                array('unit' => $sModule, 'action' => 'doVoteDown'),

                array('unit' => $sModule, 'action' => 'fan_added'),

                array('unit' => $sModule, 'action' => 'join_invitation_notif'),

                array('unit' => $sModule, 'action' => 'join_request'),

                array('unit' => $sModule, 'action' => 'timeline_post_common'),
            )
        );
    }

    /**
     * Notification about new invitation to join the group
     */
    public function serviceGetNotificationsJoinInvitation($aEvent)
    {
        $CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if(!$oGroupProfile)
            return array();

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        /*
         * It's essential that 'object_owner_id' contains invited member profile id.
         */
        $oProfile = BxDolProfile::getInstance((int)$aEvent['object_owner_id']);
        if(!$oProfile)
            return array();

        /*
         * Note. Group Profile URL is used for both Entry and Subentry URLs, 
         * because Subentry URL has higher display priority and notification
         * should be linked to Group Profile (Group Profile -> Members tab) 
         * instead of Personal Profile of invited member.
         */
        $sEntryUrl = $oGroupProfile->getUrl();

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $oGroupProfile->getDisplayName(),
            'subentry_sample' => $oProfile->getDisplayName(),
            'subentry_url' => $sEntryUrl,
            'lang_key' => $this->_oConfig->CNF['T']['txt_ntfs_join_invitation']
        );
    }

    /**
     * Notification about new member requst in the group
     */
    public function serviceGetNotificationsJoinRequest($aEvent)
    {
        return $this->_serviceGetNotification($aEvent, $this->_oConfig->CNF['T']['txt_ntfs_join_request']);
    }

	/**
     * Notification about new member in the group
     */
    public function serviceGetNotificationsFanAdded($aEvent)
    {
        return $this->_serviceGetNotification($aEvent, $this->_oConfig->CNF['T']['txt_ntfs_fan_added']);
    }

    protected function _serviceGetNotification($aEvent, $sLangKey)
    {
    	$CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if(!$oGroupProfile)
            return array();

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $oProfile = BxDolProfile::getInstance((int)$aEvent['subobject_id']);
        if(!$oProfile)
            return array();

        /*
         * Note. Group Profile URL is used for both Entry and Subentry URLs, 
         * because Subentry URL has higher display priority and notification
         * should be linked to Group Profile (Group Profile -> Members tab) 
         * instead of Personal Profile of a member, who performed an action.
         */
        $sEntryUrl = $oGroupProfile->getUrl();
        if(!empty($CNF['URL_ENTRY_FANS']))
            $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_ENTRY_FANS'], array(
                'profile_id' => $oGroupProfile->id()
            ));

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $oGroupProfile->getDisplayName(),
            'entry_author' => $oGroupProfile->id(),
            'subentry_sample' => $oProfile->getDisplayName(),
            'subentry_url' => $sEntryUrl,
            'lang_key' => $sLangKey
        );
    }

    /**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
        return BxBaseModGeneralModule::serviceGetTimelineData();
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $a = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if($a === false)
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aEvent['object_id'], $this->getName());

        $a['content']['url'] = $oGroupProfile->getUrl();
        $a['content']['title'] = $oGroupProfile->getDisplayName();

        return $a;
    }


    // ====== PERMISSION METHODS
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        return $this->serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction);
    }

    public function serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction = false, $iProfileId = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $bInvited = false;
        if(!empty($CNF['TABLE_INVITES']) && bx_get('key')){
            $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->getName());
            $mixedInvited = $this->isInvited(bx_get('key'), $oGroupProfile->id());
            if($mixedInvited === true)
                $bInvited = true;
        }

        if ($this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $iProfileId) || $bInvited)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::serviceCheckAllowedViewForProfile ($aDataEntry, $isPerformAction, $iProfileId);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedCompose(&$aDataEntry, $isPerformAction = false)
    {
        if(!$this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]))
            return _t('_sys_txt_access_denied');

        return parent::checkAllowedCompose ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanAdd(&$aDataEntry, $isPerformAction = false)
    {
        $mixedResult = $this->_modGroupsCheckAllowedFanAdd($aDataEntry, $isPerformAction);

        // call alert to allow custom checks
        bx_alert('system', 'check_allowed_fan_add', 0, 0, array(
            'module' => $this->getName(), 
            'content_info' => $aDataEntry, 
            'profile_id' => bx_get_logged_profile_id(), 
            'override_result' => &$mixedResult
        ));

        return $mixedResult;
    }

    public function _modGroupsCheckAllowedFanAdd (&$aDataEntry, $isPerformAction = false)
    {
        if ($this->isFan($aDataEntry[$this->_oConfig->CNF['FIELD_ID']]) || !isLogged())
            return _t('_sys_txt_access_denied');

        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanRemove (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], false, true, true))
            return CHECK_ACTION_RESULT_ALLOWED;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, $this->_oConfig->CNF['OBJECT_CONNECTIONS'], false, true, false);
    }

    protected function _checkAllowedActionByFan($sAction, $aDataEntry, $iProfileId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        $bRoles = $this->_oConfig->isRoles();
        if(empty($iProfileId))
            $iProfileId = bx_get_logged_profile_id();

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->getName());
        if(!$oGroupProfile)
            return $sAction == BX_BASE_MOD_GROUPS_ACTION_DELETE ? CHECK_ACTION_RESULT_ALLOWED : _t('_sys_txt_not_found');

        $iGroupProfileId = $oGroupProfile->id();

        if(!$bRoles && $this->_oDb->isAdmin($iGroupProfileId, $iProfileId, $aDataEntry))
            return CHECK_ACTION_RESULT_ALLOWED;

        if($bRoles && $this->isAllowedActionByRole($sAction, $aDataEntry, $iGroupProfileId, $iProfileId))
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    public function isAllowedActionByRole($sAction, $aDataEntry, $iGroupProfileId, $iProfileId)
    {
        $bResult = false;

        $iProfileRole = $this->_oDb->getRole($iGroupProfileId, $iProfileId);
        if(!empty($iProfileRole))
            switch($sAction) {
                case BX_BASE_MOD_GROUPS_ACTION_MANAGE_ROLES:
                    if($this->isRole($iProfileRole, BX_BASE_MOD_GROUPS_ROLE_ADMINISTRATOR))
                        $bResult = true;
                    break;

                default:
                    $bResult = true;
            }

        // call alert to allow custom checks
        bx_alert('system', 'check_allowed_action_by_role', 0, 0, array(
            'module' => $this->getName(), 
            'multi_roles' => $this->_oConfig->isMultiRoles(),
            'action' => $sAction,
            'content_profile_id' => $iGroupProfileId, 
            'content_info' => $aDataEntry, 
            'profile_id' => $iProfileId, 
            'profile_role' => $iProfileRole,
            'override_result' => &$bResult
        ));

        return $bResult;
    }

    public function isRole($iProfileRole, $iRole)
    {
        if(!$this->_oConfig->isMultiRoles())
            return $iProfileRole == $iRole;
        else 
            return $iProfileRole & (1 << ($iRole - 1));
    }

    public function serviceIsRole($iProfileRole, $iRole)
    {
        return $this->isRole($iProfileRole, $iRole);
    }

    public function checkAllowedManageFans($mixedDataEntry, $isPerformAction = false)
    {
        $aDataEntry = array();
        if(!is_array($mixedDataEntry)) {
            $oGroupProfile = BxDolProfile::getInstance((int)$mixedDataEntry);
            if($oGroupProfile && $this->getName() == $oGroupProfile->getModule())
                $aDataEntry = $this->_oDb->getContentInfoById($oGroupProfile->getContentId());
        }
        else
            $aDataEntry = $mixedDataEntry;

        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_MANAGE_FANS, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedEdit($aDataEntry, $isPerformAction);
    }

    public function checkAllowedManageAdmins($mixedDataEntry, $isPerformAction = false)
    {
        $aDataEntry = array();
        if(!is_array($mixedDataEntry)) {
            $oGroupProfile = BxDolProfile::getInstance((int)$mixedDataEntry);
            if($oGroupProfile && $this->getName() == $oGroupProfile->getModule())
                $aDataEntry = $this->_oDb->getContentInfoById($oGroupProfile->getContentId());
        }
        else
            $aDataEntry = $mixedDataEntry;

        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_MANAGE_ROLES, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedEdit($aDataEntry, $isPerformAction);
    }

    public function checkAllowedEdit($aDataEntry, $isPerformAction = false)
    {
        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_EDIT, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedEdit($aDataEntry, $isPerformAction);
    }

    public function checkAllowedInvite($aDataEntry, $isPerformAction = false)
    {
        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_INVITE, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return $this->checkAllowedEdit ($aDataEntry, $isPerformAction);
    }
    
    public function checkAllowedChangeCover($aDataEntry, $isPerformAction = false)
    {
        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_CHANGE_COVER, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedChangeCover($aDataEntry, $isPerformAction);
    }

    public function checkAllowedDelete(&$aDataEntry, $isPerformAction = false)
    {
        if($this->_checkAllowedActionByFan(BX_BASE_MOD_GROUPS_ACTION_DELETE, $aDataEntry) === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::checkAllowedDelete($aDataEntry, $isPerformAction);
    }

    public function checkAllowedJoin(&$aDataEntry, $isPerformAction = false)
    {
        if (bx_get('key')){
            $sKey = bx_get('key');
            $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
            $aData = $this->_oDb->getInviteByKey($sKey, $oGroupProfile->id());
            if (isset($aData['invited_profile_id']) && $aData['invited_profile_id'] == bx_get_logged_profile_id()){
                return CHECK_ACTION_RESULT_ALLOWED;
            }
        }   
        return _t('_sys_txt_access_denied');
    }   

    public function checkAllowedSubscribeAdd(&$aDataEntry, $isPerformAction = false)
    {
        $mixedResult = $this->_modGroupsCheckAllowedSubscribeAdd($aDataEntry, $isPerformAction);

        // call alert to allow custom checks
        bx_alert('system', 'check_allowed_subscribe_add', 0, 0, array(
            'module' => $this->getName(), 
            'content_info' => $aDataEntry, 
            'profile_id' => bx_get_logged_profile_id(), 
            'override_result' => &$mixedResult
        ));

        return $mixedResult;
    }

    /**
     * Note. Is mainly needed for internal usage. Access level is 'public' to allow outer calls from alerts.
     */
    public function _modGroupsCheckAllowedSubscribeAdd(&$aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->isFan($aDataEntry[$CNF['FIELD_ID']]) && (!isset($CNF['PARAM_SBS_WO_JOIN']) || getParam($CNF['PARAM_SBS_WO_JOIN']) != 'on'))
            return _t('_sys_txt_access_denied');

        return parent::_modProfileCheckAllowedSubscribeAdd($aDataEntry, $isPerformAction);
    }

    /**
     * @deprecated since version 11.0.3 and can be removed in the next version.
     */
    public function _checkAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false)
    {
        return parent::checkAllowedSubscribeAdd ($aDataEntry, $isPerformAction);
    }
    
    public function doAudit($iGroupProfileId, $iFanId, $sAction)
    {
        $oProfile = BxDolProfile::getInstance($iFanId);
        
        $iContentId = $oProfile->getContentId();
        $sModule = $oProfile->getModule();
        $oModule = BxDolModule::getInstance($sModule);
        if (BxDolRequest::serviceExists($sModule, 'act_as_profile') && BxDolService::call($sModule, 'act_as_profile') && $oModule->_oConfig){
            $CNF = $oModule->_oConfig->CNF;

            $aContentInfo = BxDolRequest::serviceExists($sModule, 'get_all') ? BxDolService::call($sModule, 'get_all', array(array('type' => 'id', 'id' => $iContentId))) : array();
        
            $AuditParams = array(
                'content_title' => (isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']])) ? $aContentInfo[$CNF['FIELD_TITLE']] : '',
                'context_profile_id' => $iGroupProfileId,
                'context_profile_title' => BxDolProfile::getInstance($iGroupProfileId)->getDisplayName()
            );
        
            bx_audit(
                $iContentId, 
                $sModule, 
                $sAction,  
                $AuditParams
            );
        }
    }
    
    protected function _checkAllowedConnect (&$aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap = false)
    {
        $sResult = $this->checkAllowedView($aDataEntry);

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);

        // if profile view isn't allowed but visibility is in partially visible groups 
        // then display buttons to connect (befriend, join) to profile, 
        // if other conditions (in parent::_checkAllowedConnect) are met as well
        if (CHECK_ACTION_RESULT_ALLOWED !== $sResult && !in_array($aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']], array_merge($oPrivacy->getPartiallyVisiblePrivacyGroups(), array('s'))))
            return $sResult;

        return parent::_checkAllowedConnect ($aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap);
    }

    public function addFollower ($iProfileId1, $iProfileId2)
    {
        $oConnectionFollow = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        if($oConnectionFollow && !$oConnectionFollow->isConnected($iProfileId1, $iProfileId2)){
            $oConnectionFollow->addConnection($iProfileId1, $iProfileId2);
            return true;
        }
        return false;
    }
    
    public function isFan ($iContentId, $iProfileId = false) 
    {
        $CNF = &$this->_oConfig->CNF;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->getName());
        if($oGroupProfile && isset($CNF['OBJECT_CONNECTIONS']))
            return ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])) && $oConnection->isConnected($iProfileId ? $iProfileId : bx_get_logged_profile_id(), $oGroupProfile->id(), true);

        return false;
    }

    public function isFanByGroupProfileId ($iGroupProfileId, $iProfileId = false) 
    {
        $CNF = &$this->_oConfig->CNF;

        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if($oGroupProfile && isset($CNF['OBJECT_CONNECTIONS']))
            return ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])) && $oConnection->isConnected($iProfileId ? $iProfileId : bx_get_logged_profile_id(), $oGroupProfile->id(), true);

        return false;
    }

    public function isInvited ($sKey, $iProfileId) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aData = $this->_oDb->getInviteByKey($sKey,  $iProfileId);
        if (!isset($aData['invited_profile_id']))
            return _t($CNF['T']['txt_invitation_popup_error_invitation_absent']);
        
        if ($aData['invited_profile_id'] != bx_get_logged_profile_id())
            return  _t($CNF['T']['txt_invitation_popup_error_wrong_user']);
        
        return true;
    }

    public function getRole($iGroupProfileId, $iFanProfileId)
    {
        if(!$this->isFanByGroupProfileId($iGroupProfileId, $iFanProfileId))
            return false;

        return $this->_oDb->getRole($iGroupProfileId, $iFanProfileId);
    }

    public function setRole($iGroupProfileId, $iFanProfileId, $mixedRole, $mixedPeriod = false, $sOrder = '')
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($CNF['OBJECT_CONNECTIONS']))
            return false;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if(!$oConnection || !$oGroupProfile)
            return false;

        if(!$oConnection->isConnected($iFanProfileId, $iGroupProfileId, true) && !($oConnection->addConnection($iFanProfileId, $iGroupProfileId) && $oConnection->addConnection($iGroupProfileId, $iFanProfileId)))
            return false;

        if(!$this->_oDb->setRole($iGroupProfileId, $iFanProfileId, $mixedRole, $mixedPeriod, $sOrder))
            return false;

        $this->onSetRole($iGroupProfileId, $iFanProfileId, $mixedRole);

        return true;
    }

    public function onSetRole($iGroupProfileId, $iFanProfileId, $mixedRole)
    {
        $CNF = &$this->_oConfig->CNF;

        $iProfileId = bx_get_logged_profile_id();
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        $aGroupProfileInfo = $this->_oDb->getContentInfoById((int)$oGroupProfile->getContentId());
        $aRoles = BxDolFormQuery::getDataItems($CNF['OBJECT_PRE_LIST_ROLES']);

        // notify about admin status
        if(!empty($CNF['EMAIL_FAN_SET_ROLE']) && $iFanProfileId != $iProfileId)
            sendMailTemplate($CNF['EMAIL_FAN_SET_ROLE'], 0, $iFanProfileId, array(
                'EntryUrl' => $oGroupProfile->getUrl(),
                'EntryTitle' => $oGroupProfile->getDisplayName(),
                'Role' => !is_array($mixedRole) ? $aRoles[(int)$mixedRole] : 'TODO: Add List of Roles'
            ), BX_EMAIL_NOTIFY);

        bx_alert($this->getName(), 'set_role', $aGroupProfileInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
            'object_author_id' => $iGroupProfileId,
            'performer_id' => $iProfileId, 
            'fan_id' => $iFanProfileId,

            'content' => $aGroupProfileInfo, 
            'role' => $mixedRole,

            'group_profile' => $iGroupProfileId, 
            'profile' => $iProfileId
        ));

        $this->doAudit($iGroupProfileId, $iFanProfileId, '_sys_audit_action_group_role_changed');
    }

    public function unsetRole($iGroupProfileId, $iFanProfileId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!isset($CNF['OBJECT_CONNECTIONS']))
            return false;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        if(!$oConnection || !$oGroupProfile)
            return false;

        if($oConnection->isConnected($iFanProfileId, $iGroupProfileId, true) && !$oConnection->removeConnection($iFanProfileId, $iGroupProfileId))
            return false;

        $iRole = $this->_oDb->getRole($iGroupProfileId, $iFanProfileId);

        if(!$this->_oDb->unsetRole($iGroupProfileId, $iFanProfileId))
            return false;

        $this->onUnsetRole($iGroupProfileId, $iFanProfileId, $iRole);

        return true;
    }

    public function onUnsetRole($iGroupProfileId, $iFanProfileId, $iRole)
    {
        $CNF = &$this->_oConfig->CNF;

        $iProfileId = bx_get_logged_profile_id();
        $oGroupProfile = BxDolProfile::getInstance($iGroupProfileId);
        $aGroupProfileInfo = $this->_oDb->getContentInfoById((int)$oGroupProfile->getContentId());

        bx_alert($this->getName(), 'set_role', $aGroupProfileInfo[$CNF['FIELD_ID']], $iGroupProfileId, array(
            'object_author_id' => $iGroupProfileId,
            'performer_id' => $iProfileId, 
            'fan_id' => $iFanProfileId,

            'content' => $aGroupProfileInfo,
            'role' => $iRole,

            'group_profile' => $iGroupProfileId, 
            'profile' => $iProfileId
        ));

        $this->doAudit($iGroupProfileId, $iFanProfileId, '_sys_audit_action_group_role_changed');
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aEvent['object_id'], $this->getName());

        $sSrc = '';
        if(isset($CNF['FIELD_COVER']) && !empty($aContentInfo[$CNF['FIELD_COVER']]))
            $sSrc = $oGroupProfile->getCover();

        if(empty($sSrc) && isset($CNF['FIELD_PICTURE']) && !empty($aContentInfo[$CNF['FIELD_PICTURE']]))
            $sSrc = $oGroupProfile->getPicture();

        return empty($sSrc) ? array() : array(
            array('id' => $aContentInfo[$CNF['FIELD_PICTURE']], 'url' => $sUrl, 'src' => $sSrc, 'src_orig' => $sSrc),
        );
    }

    protected function _prepareProfileAndGroupProfile($iGroupProfileId, $iInitiatorId)
    {
        if (!($oGroupProfile = BxDolProfile::getInstance($iGroupProfileId)))
            return array(0, 0, null);

        if ($oGroupProfile->getModule() == $this->getName()) {
            $iProfileId = $iInitiatorId;
            $iGroupProfileId = $oGroupProfile->id();
        } else {
            $iProfileId = $oGroupProfile->id();
            $iGroupProfileId = $iInitiatorId;
        }

        return array($iProfileId, $iGroupProfileId, $oGroupProfile);
    }
}

/** @} */
