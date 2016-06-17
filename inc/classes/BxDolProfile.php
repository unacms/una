<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_PROFILE_ACTION_AUTO', 0); ///< automatic action without any checking
define('BX_PROFILE_ACTION_MANUAL', 1); ///< manual action performed by human
define('BX_PROFILE_ACTION_ROBOT', 2); ///< action peformed by some robot based on some conditions
define('BX_PROFILE_ACTION_EXTERNAL', 2); ///< action peformed by external service, like join using OAuth 

class BxDolProfile extends BxDol implements iBxDolProfile
{
    protected $_iProfileID;
    protected $_oQuery;

    /**
     * Constructor
     */
    protected function __construct ($iProfileId)
    {
        $iProfileId = (int)$iProfileId;
        $sClass = get_class($this) . '_' . $iProfileId;
        if (isset($GLOBALS['bxDolClasses'][$sClass]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_iProfileID = $iProfileId; // since constructor is protected $iProfileId is always valid
        $this->_oQuery = BxDolProfileQuery::getInstance();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        $sClass = get_class($this) . '_' . $this->_iProfileID;
        if (isset($GLOBALS['bxDolClasses'][$sClass]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of Account Profile by account id
     */
    public static function getInstanceAccountProfile($iAccountId = false)
    {
        if (!$iAccountId)
            $iAccountId = getLoggedId();
        $oQuery = BxDolProfileQuery::getInstance();
        $aProfile = $oQuery->getProfileByContentTypeAccount($iAccountId, 'system', $iAccountId);
        if (!$aProfile)
            return false;
        return self::getInstance($aProfile['id']);
    }

    /**
     * Get singleton instance of Profile by account id, content id and type
     */
    public static function getInstanceByContentTypeAccount($iContent, $sType, $iAccountId = false)
    {
        if (!$iAccountId)
            $iAccountId = getLoggedId();
        $oQuery = BxDolProfileQuery::getInstance();
        $aProfile = $oQuery->getProfileByContentTypeAccount($iContent, $sType, $iAccountId);
        if (!$aProfile)
            return false;
        return self::getInstance($aProfile['id']);
    }

    /**
     * Get singleton instance of Profile by content id and type
     */
    public static function getInstanceByContentAndType($iContent, $sType)
    {
        $oQuery = BxDolProfileQuery::getInstance();
        $aProfile = $oQuery->getProfileByContentAndType($iContent, $sType);
        if (!$aProfile)
            return false;
        return self::getInstance($aProfile['id']);
    }

    /**
     * Get singleton instance of Profile by Account id (currently active profile is returned)
     */
    public static function getInstanceByAccount($iAccountId = false)
    {
        $oQuery = BxDolProfileQuery::getInstance();
        $mixedProfileId = $oQuery->getCurrentProfileByAccount($iAccountId);

        return self::getInstance($mixedProfileId);
    }

    /**
     * Get singleton instance of Profile by profile id
     */
    public static function getInstance($mixedProfileId = false)
    {
        if (!$mixedProfileId) {
            $oQuery = BxDolProfileQuery::getInstance();
            $mixedProfileId = $oQuery->getCurrentProfileByAccount(getLoggedId());
        }

        $iProfileId = self::getID($mixedProfileId);
        if (!$iProfileId)
            return false;

        $sClass = __CLASS__ . '_' . $iProfileId;
        if (!isset($GLOBALS['bxDolClasses'][$sClass]))
            $GLOBALS['bxDolClasses'][$sClass] = new BxDolProfile($iProfileId);

        return $GLOBALS['bxDolClasses'][$sClass];
    }

    /**
     * Get profile id
     */
    public function id()
    {
        return $this->_oQuery->getIdById($this->_iProfileID);
    }

    /**
     * Get account id associated with the profile
     */
    public function getAccountId()
    {
        $aInfo = $this->getInfo();
        return $aInfo['account_id'];
    }

    /**
     * Get account object associated with the profile
     */
    public function getAccountObject()
    {
        return BxDolAccount::getInstance($this->getAccountId());
    }

    /**
     * Get content id associated with the profile
     */
    public function getContentId()
    {
        $aInfo = $this->getInfo();
        return $aInfo['content_id'];
    }

    /**
     * Check if profile status is active
     */
    public function isActive($iProfileId = false)
    {
        return BX_PROFILE_STATUS_ACTIVE == $this->getStatus($iProfileId);
    }

    /**
     * Get profile status
     */
    public function getStatus($iProfileId = false)
    {
        $aInfo = $this->_oQuery->getInfoById((int)$iProfileId ? $iProfileId : $this->_iProfileID);
        return $aInfo['status'];
    }

    /**
     * Get profile module name
     */
    public function getModule($iProfileId = false)
    {
        $aInfo = $this->_oQuery->getInfoById((int)$iProfileId ? $iProfileId : $this->_iProfileID);
        return $aInfo['type'];
    }

    /**
     * Get profile info
     */
    public function getInfo($iProfileId = 0)
    {
        return $this->_oQuery->getInfoById((int)$iProfileId ? $iProfileId : $this->_iProfileID);
    }

    /**
     * Validate profile id.
     * @param $s - profile id
     * @return profile id or false if profile was not found
     */
    static public function getID($s)
    {
        $iId = BxDolProfileQuery::getInstance()->getIdById((int)$s);
        return $iId ? $iId : false;
    }

    /**
     * Get name to display in thumbnail
     */
    public function getDisplayName($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_name', array($aInfo['content_id']));
    }

    /**
     * Get profile url
     */
    public function getUrl($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_url', array($aInfo['content_id']));
    }

    /**
     * Get profile unit
     */
    public function getUnit($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_unit', array($aInfo['content_id']));
    }

    /**
     * Get picture url
     */
    public function getPicture($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_picture', array($aInfo['content_id']));
    }

    /**
     * Get avatar url
     */
    public function getAvatar($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_avatar', array($aInfo['content_id']));
    }

    /**
     * Get thumbnail url
     */
    public function getThumb($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_thumb', array($aInfo['content_id']));
    }

    /**
     * Get icon url
     */
    public function getIcon($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_icon', array($aInfo['content_id']));
    }

    /**
     * Get module icon
     */
    public function getIconModule($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'module_icon');
    }

    /**
     * get profile edit page url
     */
    public function getEditUrl($iProfileId = 0)
    {
        $aInfo = $this->getInfo($iProfileId);
        return BxDolService::call($aInfo['type'], 'profile_edit_url', array($aInfo['content_id']));
    }

    /**
     * Delete profile.
     * @param $ID - optional profile id to delete
     * @param $bDeleteWithContent - delete profile with all its contents
     * @param $bForceDelete - force deletetion is case of account profile deletion
     * @return false on error, or true on success
     */
    function delete($ID = false, $bDeleteWithContent = false, $bForceDelete = false)
    {
        $ID = (int)$ID;
        if (!$ID)
            $ID = $this->_iProfileID;

        $aProfileInfo = $this->_oQuery->getInfoById($ID);
        if (!$aProfileInfo)
            return false;

        // delete system profiles (accounts) is not allowed, instead - delete whole account
        if (!$bForceDelete && 'system' == $aProfileInfo['type'])
            return false;

        // delete actual profile
        if ($sErrorMsg = BxDolService::call($aProfileInfo['type'] , 'delete_entity_service', array($aProfileInfo['content_id'], $bDeleteWithContent)))
            return false;

        // switch profile context if deleted profile is active profile context
        $oAccount = BxDolAccount::getInstance ($aProfileInfo['account_id']);
        $aAccountInfo = $oAccount->getInfo();
        if (!$bForceDelete && $ID == $aAccountInfo['profile_id']) {

            $aProfiles = $oAccount->getProfiles();            
            $oProfileAccount = BxDolProfile::getInstanceAccountProfile($aProfileInfo['account_id']);

            // unset deleted profile and account profile
            unset($aProfiles[$ID]);
            unset($aProfiles[$oProfileAccount->id()]);

            if ($aProfiles) {
                // try to use another profile
                reset($aProfiles);
                $iProfileId = key($aProfiles);
            } 
            else {
                // if no profiles exist, use account profile
                $iProfileId = $oProfileAccount->id();
            }

            $oAccount->updateProfileContext($iProfileId);
        }

        // create system event before deletion
        $isStopDeletion = false;
        bx_alert('profile', 'before_delete', $ID, 0, array('delete_with_content' => $bDeleteWithContent, 'stop_deletion' => &$isStopDeletion));
        if ($isStopDeletion)
            return false;

        // delete associated comments
        if($bDeleteWithContent)
	        BxDolCmts::onAuthorDelete($ID);

        // delete connections
        $oConn = BxDolConnection::getObjectInstance('sys_profiles_friends');
        $oConn->onDeleteInitiatorAndContent($ID);

        $oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        $oConn->onDeleteInitiatorAndContent($ID);

        // delete profile's acl levels
        BxDolAcl::getInstance()->onProfileDelete($ID);

        // delete profile
        if (!$this->_oQuery->delete($ID))
            return false;

        // create system event
        bx_alert('profile', 'delete', $ID, 0, array('delete_with_content' => $bDeleteWithContent));

        // unset class instance to prevent creating the instance again
        $this->_iProfileID = 0;
        $sClass = get_class($this) . '_' . $ID;
        unset($GLOBALS['bxDolClasses'][$sClass]);

        return true;
    }

    /**
     * Insert account and content id association. Also if currect profile id is not defined - it updates current profile id in account.
     * @param $iAccountId account id
     * @param $iContentId content id
     * @param $sStatus profile status
     * @param $sType profile content type
     * @return inserted profile's id
     */
    static public function add ($iAction, $iAccountId, $iContentId, $sStatus, $sType = 'system')
    {
        $oQuery = BxDolProfileQuery::getInstance();
        if (!($iProfileId = $oQuery->insertProfile ($iAccountId, $iContentId, $sStatus, $sType)))
            return false;
        bx_alert('profile', 'add', $iProfileId, 0, array('module' => $sType, 'content' => $iContentId, 'account' => $iAccountId, 'status' => $sStatus, 'action' => $iAction));
        return $iProfileId;
    }

    /**
     * Change profile status to 'Active'
     */
    public function activate($iAction, $iProfileId = 0, $bSendEmailNotification = true)
    {
        $sStatus = $this->getStatus($iProfileId);
        return $this->changeStatus(BX_PROFILE_STATUS_ACTIVE, BX_PROFILE_STATUS_PENDING == $sStatus ? 'approve' : 'activate', $iAction, $iProfileId, $bSendEmailNotification);
    }

    /**
     * Change profile status from 'Pending' to the next level - 'Active'
     */
    public function approve($iAction, $iProfileId = 0, $bSendEmailNotification = true)
    {
        return $this->changeStatus(BX_PROFILE_STATUS_ACTIVE, 'approve', $iAction, $iProfileId, $bSendEmailNotification);
    }

    /**
     * Change profile status to 'Pending'
     */
    public function disapprove($iAction, $iProfileId = 0, $bSendEmailNotification = true)
    {
        return $this->changeStatus(BX_PROFILE_STATUS_PENDING, 'disapprove', $iAction, $iProfileId, $bSendEmailNotification);
    }

    /**
     * Change profile status to 'Suspended'
     */
    public function suspend($iAction, $iProfileId = 0, $bSendEmailNotification = true)
    {
        return $this->changeStatus(BX_PROFILE_STATUS_SUSPENDED, 'suspend', $iAction, $iProfileId, $bSendEmailNotification);
    }

    protected function changeStatus($sStatus, $sAlertActionName, $iAction, $iProfileId = 0, $bSendEmailNotification = true)
    {
        if (!$iProfileId)
            $iProfileId = $this->_iProfileID;

        // get account and profile objects
        $oProfile = BxDolProfile::getInstance($iProfileId);
        $oAccount = $oProfile->getAccountObject();
        if (!$oProfile || !$oAccount)
            return false;

        // change status
        if (!$this->_oQuery->changeStatus($iProfileId, $sStatus))
            return false;

        // alert about status changing
        bx_alert('profile', $sAlertActionName, $iProfileId, false, array('action' => $iAction));

        // send email to member about status change
        if ($bSendEmailNotification)
            sendMailTemplate('t_ChangeStatus' . ucfirst($sStatus), $oAccount->id(), $iProfileId, array('status' => $sStatus), BX_EMAIL_SYSTEM);

        return true;
    }

    /**
     * Display informer message if it is possible to switch to this profile
     */
    public function checkSwitchToProfile($oTemplate = null, $iViewerAccountId = false, $iViewerProfileId = false)
    {
        if (false === $iViewerAccountId)
            $iViewerAccountId = getLoggedId();
        if (false === $iViewerProfileId)
            $iViewerProfileId = bx_get_logged_profile_id();

        if (!$iViewerAccountId || !$iViewerProfileId)
            return;

        if ($iViewerAccountId != $this->getAccountId() ||  $iViewerProfileId == $this->id())
            return;

        $oInformer = BxDolInformer::getInstance($oTemplate);
        if ($oInformer)
            $oInformer->add('sys-switch-profile-context', _t('_sys_txt_account_profile_context_change_suggestion', BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher', array('switch_to_profile' => $this->id()))), BX_INFORMER_INFO);

    }

	/**
     * Add permament messages.
     */
    public function addInformerPermanentMessages ($oInformer)
    {
    	$aInfo = $this->getInfo();
    	$aProfiles = $this->_oQuery->getProfilesByAccount($aInfo['account_id']);

        if ($aInfo['type'] == 'system' && count($aProfiles) == 1) {
            $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher');
            $oInformer->add('sys-account-profile-system', _t('_sys_txt_account_profile_system', $sUrl), BX_INFORMER_ALERT);
        }
    }
}

/** @} */
