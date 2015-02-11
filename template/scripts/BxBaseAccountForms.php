<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * System profile(account) forms functions
 * @see BxDolProfileForms
 */
class BxBaseAccountForms extends BxDolProfileForms
{
    protected $_iProfileId;

    public function __construct()
    {
        parent::__construct();
        $this->_iProfileId = bx_get_logged_profile_id();
    }

    public function createAccountForm ()
    {
        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = BxDolAccount::isAllowedCreate (0)))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('sys_account', 'sys_account_create');
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->initChecker();
        if (!$oForm->isSubmittedAndValid()) {
        	$sCode = $oForm->getCode();

        	bx_alert('account', 'add_form', 0, 0, array(
        		'form_object' => &$oForm,
        		'form_code' => &$sCode
        	));

            return $sCode;
        }

        // insert data into database
        $aValsToAdd = array (
            'email_confirmed' => 0,
        );
        $iAccountId = $oForm->insert ($aValsToAdd);
        if (!$iAccountId) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_account_creation'));
        }

        // alert
        bx_alert('account', 'add', $iAccountId, 0);

        // if email_confirmation procedure is enabled - send email confirmation letter
        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (getParam('sys_email_confirmation') && $oAccount && !$oAccount->isConfirmed())
            $oAccount->sendConfirmationEmail($iAccountId);

        // add account and content association
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, $iAccountId, $iAccountId, BX_PROFILE_STATUS_PENDING, 'system');
        $oProfile = BxDolProfile::getInstance($iProfileId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        $isAutoApprove = $oForm->isSetPendingApproval() ? false : true;
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $isAutoApprove)
            $oProfile->approve(BX_PROFILE_ACTION_AUTO);

        // perform action
        BxDolAccount::isAllowedCreate ($iProfileId, true);

        // alert
        bx_alert('account', 'added', $iAccountId);

        // login to the created account automatically
        bx_login($iAccountId);
        $this->_iProfileId = bx_get_logged_profile_id();

        // redirect
        $this->_redirectAndExit(getParam('sys_redirect_after_account_added'), true, array(
            'account_id' => $iAccountId,
            'profile_id' => $iProfileId,
        ));
    }

    public function editAccountEmailSettingsForm ($iAccountId)
    {
        return $this->_editAccountForm ($iAccountId, 'sys_account_settings_email');
    }

    public function editAccountPasswordSettingsForm ($iAccountId)
    {
        return $this->_editAccountForm ($iAccountId, 'sys_account_settings_pwd');
    }

    public function editAccountInfoForm ($iAccountId)
    {
        return $this->_editAccountForm ($iAccountId, 'sys_account_settings_info');
    }

    public function deleteAccountForm ($iAccountId)
    {
        $oAccount = BxDolAccount::getInstance($iAccountId);
        $aAccountInfo = $oAccount ? $oAccount->getInfo() : false;
        if (!$aAccountInfo)
            return MsgBox(_t('_sys_txt_error_account_is_not_defined'));

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = BxDolAccount::isAllowedDelete ($this->_iProfileId, $aAccountInfo)))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('sys_account', 'sys_account_settings_del_account');
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        if (!$oForm->isSubmitted())
            unset($aAccountInfo['password']);

        $oForm->initChecker($aAccountInfo);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // delete account
        $oAccount = BxDolAccount::getInstance($aAccountInfo['id']);
        if (!$oAccount->delete())
            return MsgBox(_t('_sys_txt_error_account_delete'));

        // logout from deleted account
        if($iAccountId == getLoggedId())
        	bx_logout();

        // redirect to homepage
        $this->_redirectAndExit('', false);
    }

    protected function _editAccountForm ($iAccountId, $sDisplayName)
    {
        $oAccount = BxDolAccount::getInstance($iAccountId);
        $aAccountInfo = $oAccount ? $oAccount->getInfo() : false;
        if (!$aAccountInfo)
            return MsgBox(_t('_sys_txt_error_account_is_not_defined'));

        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = BxDolAccount::isAllowedEdit ($this->_iProfileId, $aAccountInfo)))
            return MsgBox($sMsg);

        // check and display form
        $oForm = BxDolForm::getObjectInstance('sys_account', $sDisplayName);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        if (!$oForm->isSubmitted())
            unset($aAccountInfo['password']);

        $oForm->initChecker($aAccountInfo);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        $aTrackTextFieldsChanges = array (); // track text fields changes, not-null(for example empty array) - means track, null - means don't track

        // update email and email setting in DB
        if (!$oForm->update ($aAccountInfo['id'], array(), $aTrackTextFieldsChanges)) {
            if (!$oForm->isValid())
                return $oForm->getCode();
            else
                return MsgBox(_t('_sys_txt_error_account_update'));
        }

        // check if email was changed
        if (!empty($aTrackTextFieldsChanges['changed_fields']) && in_array('email', $aTrackTextFieldsChanges['changed_fields']))
            $oAccount->updateEmailConfirmed(false);  // mark email as unconfirmed

        // check if password was changed
        if ($oForm->getCleanValue('password')) {
            // relogin with new password
            bx_logout();
            bx_login($aAccountInfo['id']);
        }

        // check if other text info was changed - if auto-appproval is off
        $isAutoApprove = $oForm->isSetPendingApproval() ? false : true;
        if (!$isAutoApprove) {
            $oProfile = BxDolProfile::getInstanceAccountProfile($aAccountInfo['id']); // get profile associated with account, not current porfile
            $aProfileInfo = $oProfile->getInfo();
            unset($aTrackTextFieldsChanges['changed_fields']['email']); // email confirmation is automatic and separate, don't need to deactivate whole profile if email is changed
            if (BX_PROFILE_STATUS_ACTIVE == $aProfileInfo['status'] && !empty($aTrackTextFieldsChanges['changed_fields']))
                $oProfile->disapprove(BX_PROFILE_ACTION_AUTO);  // change profile to 'pending' only if some text fields were changed and profile is active
        }

        // create an alert
        bx_alert('account', 'edited', $aAccountInfo['id'], $aAccountInfo['id'], array('display' => $sDisplayName));

        // display result message
        $sMsg = MsgBox(_t('_sys_txt_data_successfully_submitted'));
        return $sMsg . $oForm->getCode();
    }

}

/** @} */
