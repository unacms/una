<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolAccount extends BxDol
{
    protected $_iAccountID;
    protected $_oQuery;

    /**
     * Constructor
     */
    protected function __construct ($iAccountId)
    {
        $iAccountId = (int)$iAccountId;
        $sClass = get_class($this) . '_' . $iAccountId;
        if (isset($GLOBALS['bxDolClasses'][$sClass]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_iAccountID = $iAccountId; // since constructor is protected $iAccountId is always valid
        $this->_oQuery = BxDolAccountQuery::getInstance();
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
     * Get singleton instance of the class
     */
    public static function getInstance($mixedAccountId = false)
    {
        if (!$mixedAccountId)
            $mixedAccountId = getLoggedId();

        $iAccountId = self::getID($mixedAccountId);
        if (!$iAccountId)
            return false;

        $sClass = __CLASS__ . '_' . $iAccountId;
        if(!isset($GLOBALS['bxDolClasses'][$sClass]))
            $GLOBALS['bxDolClasses'][$sClass] = new BxDolAccount($iAccountId);

        return $GLOBALS['bxDolClasses'][$sClass];
    }

    /**
     * Get studio operator account singleton instance on the class
     */
    public static function getInstanceStudioOperator()
    {
        $oQuery = BxDolAccountQuery::getInstance();
        if (!($iId = $oQuery->getStudioOperatorId()))
            return false;

        return self::getInstance($iId);
    }

    /**
     * Get account id
     */
    public function id()
    {
        return $this->_oQuery->getIdById($this->_iAccountID);
    }

    /**
     * Check if account is confirmed, it is checked by email confirmation
     */
    public function isConfirmed($iAccountId = false)
    {
        if (!getParam('sys_email_confirmation')) // if email_confirmation procedure is disabled, always return true
            return true;
        $a = $this->getInfo((int)$iAccountId);
        return $a['email_confirmed'] ? true : false;
    }

    /**
     * Set account email to confirmed or unconfirmed
     * @param  int  $isConfirmed - false: mark email as unconfirmed, true: as confirmed
     * @param  int  $iAccountId  - optional account id
     * @return true on success or false on error
     */
    public function updateEmailConfirmed($isConfirmed, $isAutoSendConfrmationEmail = true, $iAccountId = false)
    {
        $iId = (int)$iAccountId ? (int)$iAccountId : $this->_iAccountID;

        if (!$isConfirmed && $isAutoSendConfrmationEmail && getParam('sys_email_confirmation')) // if email_confirmation procedure is enabled - send email confirmation letter
            $this->sendConfirmationEmail($iId);

        if ($this->_oQuery->updateEmailConfirmed($isConfirmed, $iId)) {
            bx_alert('account', $isConfirmed ? 'confirm' : 'unconfirm', $iId);
            return true;
        }
        return false;
    }

    public function updateProfileContext($iSwitchToProfileId, $iAccountId = false)
    {
        $iId = (int)$iAccountId ? (int)$iAccountId : $this->_iAccountID;
        $aInfo = $this->getInfo((int)$iId);
        if (!$aInfo)
            return false;

        $ret = null;
        bx_alert('account', 'before_switch_context', $iId, $iSwitchToProfileId, array('profile_id_current' => $aInfo['profile_id'], 'override_result' => &$ret));
        if ($ret !== null)
            return $ret;

        if (!$this->_oQuery->updateCurrentProfile($iId, $iSwitchToProfileId))
            return false;

        bx_alert('account', 'switch_context', $iId, $iSwitchToProfileId, array('profile_id_old' => $aInfo['profile_id']));

        return true;
    }

    /**
     * Send "confirmation" email
     */
    public function sendConfirmationEmail($iAccountId = false)
    {
        $sEmail = $this->getEmail($iAccountId);

        $oKey = BxDolKey::getInstance();
        $sConfirmationCode = $oKey->getNewKey(array('account_id' => $iAccountId));

        $sConfirmationLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-email') . '&code=' . urlencode($sConfirmationCode);

        $aPlus = array();
        $aPlus['email'] = $sEmail;
        $aPlus['conf_code'] = $sConfirmationCode;
        $aPlus['conf_link'] = $sConfirmationLink;
        $aPlus['conf_form_link'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-email');

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_Confirmation', $aPlus);
        return $aTemplate && sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, array(), BX_EMAIL_SYSTEM);
    }

    /**
     * Get account info
     */
    public function getInfo($iAccountId = false)
    {
        return $this->_oQuery->getInfoById((int)$iAccountId ? (int)$iAccountId : $this->_iAccountID);
    }

    /**
     * Get account display name
     */
    public function getDisplayName($iAccountId = false)
    {
        $aInfo = $this->getInfo($iAccountId);
        return $aInfo['name'];
    }

    /**
     * Get account url
     */
    public function getUrl($iAccountId = false)
    {
        return 'javascript:void(0);';
    }

    /**
     * Get account url
     */
    public function getUnit($iAccountId = false)
    {
        return '<div>' . $this->getDisplayName($iAccountId) . '</div>';
    }

    /**
     * Get avatar picture url
     */
    public function getAvatar($iAccountId = false)
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-avatar.png');
    }

    /**
     * Get thumb picture url
     */
    public function getThumb($iAccountId = false)
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-thumb.png');
    }

    /**
     * Get icon picture url
     */
    public function getIcon($iAccountId = false)
    {
        return BxDolTemplate::getInstance()->getImageUrl('account-icon.png');
    }

    /**
     * Get account email
     */
    public function getEmail($iAccountId = false)
    {
        $iAccountId = (int)$iAccountId ? (int)$iAccountId : $this->_iAccountID;
        $aAccountInfo = $this->getInfo($iAccountId);
        return $aAccountInfo['email'];
    }

    /**
     * Validate account.
     * @param $s - account identifier (id or email)
     * @return account id or false if account was not found
     */
    static public function getID($s)
    {
        $oQuery = BxDolAccountQuery::getInstance();

        if (preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/i", $s)) {
            $iId = (int)$oQuery->getIdByEmail($s);
            return $iId ? $iId : false;
        }

        $iId = $oQuery->getIdById((int)$s);
        return $iId ? $iId : false;
    }

    /**
     * Delete profile.
     * @param $bDeleteWithContent - delete associated profiles with all its contents
     */
    function delete($bDeleteWithContent = false)
    {
        $aAccountInfo = $this->_oQuery->getInfoById($this->_iAccountID);
        if (!$aAccountInfo)
            return false;

        // create system event before deletion
        $isStopDeletion = false;
        bx_alert('account', 'before_delete', $this->_iAccountID, 0, array('delete_with_content' => $bDeleteWithContent, 'stop_deletion' => &$isStopDeletion));
        if ($isStopDeletion)
            return false;

        $oAccountQuery = BxDolAccountQuery::getInstance();

        $oProfileQuery = BxDolProfileQuery::getInstance();
        $aProfiles = $oProfileQuery->getProfilesByAccount($this->_iAccountID);
        foreach ($aProfiles as $iProfileId => $aRow) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if (!$oProfile)
                continue;
            $oProfile->delete(false, $bDeleteWithContent, true);
        }

        // delete profile
        if (!$oAccountQuery->delete($this->_iAccountID))
            return false;

        // create system event
        bx_alert('account', 'delete', $this->_iAccountID, 0, array ('delete_with_content' => $bDeleteWithContent));

        // unset class instance to prevent creating the instance again
        $this->_iAccountID = 0;
        $sClass = get_class($this) . '_' . $this->_iAccountID;
        unset($GLOBALS['bxDolClasses'][$sClass]);

        return true;
    }

    /**
     * Add permament messages.
     */
    public function addInformerPermanentMessages ($oInformer)
    {
        if (!$this->isConfirmed()) {
            $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-email') . '&resend=1';
            $aAccountInfo = $this->getInfo();
            $oInformer->add('sys-account-unconfirmed', _t('_sys_txt_account_unconfirmed', $sUrl, $aAccountInfo['email']), BX_INFORMER_ALERT);
        }
    }

    /**
     * Get unsubscribe link for the specified mesage type
     */
    public function getUnsubscribeLink($iEmailType, $iAccountId = false)
    {
        $iAccountId = (int)$iAccountId ? (int)$iAccountId : $this->_iAccountID;
        $sUrl = '';
        switch ($iEmailType) {
            case BX_EMAIL_NOTIFY:
                $sUrl = 'page.php?i=unsubscribe-notifications';
                break;
            case BX_EMAIL_MASS:
                $sUrl = 'page.php?i=unsubscribe-news';
                break;
            default:
                return '';
        }
        return BxDolPermalinks::getInstance()->permalink($sUrl) . '&id=' . $iAccountId . '&code=' . $this->getEmailHash();
    }

    public function getEmailHash($iAccountId = false)
    {
        $iAccountId = (int)$iAccountId ? (int)$iAccountId : $this->_iAccountID;
        $a = $this->getInfo();
        return md5($a['email'] . $a['salt'] . BX_DOL_SECRET);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    static public function isAllowedCreate ($iProfileId, $isPerformAction = false)
    {
        $aCheck = checkActionModule($iProfileId, 'create account', 'system', $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($aCheck[CHECK_ACTION_MESSAGE]);
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    static public function isAllowedEdit ($iProfileId, $aContentInfo, $isPerformAction = false)
    {
        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            return _t('_sys_txt_access_denied');

        $aProfileInfo = $oProfile->getInfo();
        if (!$aProfileInfo || getLoggedId() != $aProfileInfo['account_id'])
            return _t('_sys_txt_access_denied');

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    static public function isAllowedDelete ($iProfileId, $aContentInfo, $isPerformAction = false)
    {
        $aCheck = checkActionModule($iProfileId, 'delete account', 'system', $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($aCheck[CHECK_ACTION_MESSAGE]);
        return CHECK_ACTION_RESULT_ALLOWED;
    }

}

/** @} */
