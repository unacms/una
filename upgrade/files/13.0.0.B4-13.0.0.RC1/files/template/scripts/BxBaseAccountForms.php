<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System profile(account) forms functions
 * @see BxDolProfileForms
 */
class BxBaseAccountForms extends BxDolProfileForms
{
    protected $_iProfileId;

    static public $PROFILE_FIELDS = array();

    public function __construct()
    {
        parent::__construct();
        $this->_iProfileId = bx_get_logged_profile_id();
    }

    public function getObjectFormAdd ()
    {
        return BxDolForm::getObjectInstance('sys_account', 'sys_account_create');
    }

    public function getObjectFormEdit ()
    {
        return BxDolForm::getObjectInstance('sys_account', 'sys_account_settings_info');
    }

    public function getObjectFormDelete ()
    {
        return BxDolForm::getObjectInstance('sys_account', 'sys_account_settings_del_account');
    }

    public function createAccountForm ($aParams = array())
    {
        // check access
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = BxDolAccount::isAllowedCreate (0)))
            return MsgBox($sMsg);

        // check and display form
        $oForm = $this->getObjectFormAdd ();
        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        $oForm->aFormAttrs['action'] = !empty($aParams['action']) ? $aParams['action'] : bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=create-account'));
        $oForm->initChecker(self::$PROFILE_FIELDS);

        bx_alert('account', 'add_form_check', 0, 0, array(
            'form_object' => &$oForm
        ));

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

        $iProfileId = $this->onAccountCreated($iAccountId, $oForm->isSetPendingApproval());

        $sRelocateCustom = $oForm->getCleanValue('relocate');
        $bRelocateCustom = !empty($sRelocateCustom);

        // perform action
        BxDolAccount::isAllowedCreate ($iProfileId, true);

        $this->_iProfileId = bx_get_logged_profile_id();

        // check and redirect
        $aModulesProfile = array(); 
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule) {
        	$oModule = BxDolModule::getInstance($aModule['name']);
        	if($oModule instanceof iBxDolProfileService && BxDolService::call($aModule['name'], 'act_as_profile') === true)
        		$aModulesProfile[] = $aModule;
        }

        $sDefaultProfileType = getParam('sys_account_default_profile_type');
        if(count($aModulesProfile) == 1)
        	$sProfileModule = $aModulesProfile[0]['name'];
        else if(!empty($sDefaultProfileType)) 
            $sProfileModule = $sDefaultProfileType;

        if (getParam('sys_account_auto_profile_creation') && !empty($sProfileModule)) {
            $oAccount = BxDolAccount::getInstance($iAccountId);
            $aProfileInfo = BxDolService::call($sProfileModule, 'prepare_fields', array(array(
                'author' => $iProfileId,
                'name' => $oAccount->getDisplayName(),
            )));
            
            $a = BxDolService::call($sProfileModule, 'entity_add', array($iProfileId, $aProfileInfo));

            // in case of successful profile add redirect to the page after profile creation
            if (0 == $a['code']) {
                if($bRelocateCustom)
                    $this->_redirectAndExit($sRelocateCustom, false);

                BxDolService::call($sProfileModule, 'redirect_after_add', array($a['content']));
                return;
            }
            // if creation failed, redirect to create profile form
        }

        $sRelocate = !empty($sProfileModule) ? BxDolService::call($sProfileModule, 'profile_create_url', array(false)) : '';
        if(empty($sRelocate))
            $sRelocate = $bRelocateCustom ? $sRelocateCustom : getParam('sys_redirect_after_account_added');
    
        $this->_redirectAndExit($sRelocate, true, array(
            'account_id' => $iAccountId,
            'profile_id' => $iProfileId,
        ));

    }

    public function onAccountCreated ($iAccountId, $isSetPendingApproval, $iAction = BX_PROFILE_ACTION_MANUAL, $bNeedToLogin = true)
    {
        // alert
        bx_alert('account', 'add', $iAccountId, 0);

        // if email_confirmation procedure is enabled - send email confirmation letter
        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (BxDolAccount::isNeedConfirmEmail() && $oAccount && !$oAccount->isConfirmedEmail())
            $oAccount->sendConfirmationEmail($iAccountId);

        // add account and content association
        $iProfileId = BxDolProfile::add(BX_PROFILE_ACTION_MANUAL, $iAccountId, $iAccountId, BX_PROFILE_STATUS_PENDING, 'system');
        $oProfile = BxDolProfile::getInstance($iProfileId);

        // approve profile if auto-approval is enabled and profile status is 'pending'
        $sStatus = $oProfile->getStatus();
        $isAutoApprove = !$isSetPendingApproval;
        if ($sStatus == BX_PROFILE_STATUS_PENDING && $isAutoApprove)
            $oProfile->approve(BX_PROFILE_ACTION_AUTO, $iProfileId, getParam('sys_account_activation_letter') == 'on');

        // alert
        bx_alert('account', 'added', $iAccountId);

        // login to the created account automatically
        if ($bNeedToLogin)
            bx_login($iAccountId);

        return $iProfileId;
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
        $oForm = $this->getObjectFormDelete();
        if(bx_get('content') !== false)
            $oForm->aInputs['delete_content']['value'] = (int)bx_get('content');

        if (!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));

        if (!$oForm->isSubmitted())
            unset($aAccountInfo['password']);

        $oForm->initChecker($aAccountInfo);

        if (!$oForm->isSubmittedAndValid())
            return $oForm->getCode();

        // delete account
        $oAccount = BxDolAccount::getInstance($aAccountInfo['id']);
        if (!$oAccount->delete(false === bx_get('delete_content') ? true : (int)$oForm->getCleanValue('delete_content') != 0))
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
        if (!empty($aTrackTextFieldsChanges['changed_fields']) && in_array('email', $aTrackTextFieldsChanges['changed_fields'])){
            $oAccount = BxDolAccount::getInstance($iAccountId, true); // refresh account to clear cache 
            $oAccount->updateEmailConfirmed(false);  // mark email as unconfirmed
        }

        // check if password was changed
        if ($oForm->getCleanValue('password')) {
            // relogin with new password
			bx_alert('account', 'edited', $aAccountInfo['id'], $aAccountInfo['id'], array('action' => 'change_password'));
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
        $sMsg = MsgBox(_t('_' . $sDisplayName . '_successfully_submitted'));
        return $sMsg . $oForm->getCode();
    }    

}

/** @} */
