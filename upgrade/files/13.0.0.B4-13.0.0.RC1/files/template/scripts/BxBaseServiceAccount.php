<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System service for creating system profile functionality.
 */

class BxBaseServiceAccount extends BxDol
{
    protected $_oAccountForms;
    protected $_oAccountQuery;

    public function __construct()
    {
        parent::__construct();

        $this->_oAccountForms = new BxTemplAccountForms();
        $this->_oAccountQuery = BxDolAccountQuery::getInstance();
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-account Account
     * @subsubsection bx_system_general-create_account_form create_account_form
     * 
     * @code bx_srv('system', 'create_account_form', [], 'TemplServiceAccount'); @endcode
     * @code {{~system:create_account_form:TemplServiceAccount[{"no_login_text":true, "no_auth_buttons":true}]~}} @endcode
     * 
     * Join form.
     * @param $aParams array of additional params, such as: 
     *          - no_login_text
     *          - no_auth_buttons
     * 
     * @see BxBaseServiceAccount::serviceGetCreatePostForm
     */
    /** 
     * @ref bx_system_general-create_account_form "create_account_form"
     */
    public function serviceCreateAccountForm ($aParams = array())
    {
        if (isLogged() && 'create-account' == bx_get('i')){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }

        $oPemalink = BxDolPermalinks::getInstance();

        if (isset($_SERVER['HTTP_REFERER']) && 0 === mb_stripos($_SERVER['HTTP_REFERER'], BX_DOL_URL_ROOT)) { // remember referrer
            $sJoinReferrer = $_SERVER['HTTP_REFERER'];
            $aNoRelocatePages = array('forgot-password', 'login', 'create-account', 'logout');
            foreach ($aNoRelocatePages as $s) {
                if (false !== mb_stripos($sJoinReferrer, $s)) {
                    $sJoinReferrer = '';
                    break;
                }
            }   
            if ($sJoinReferrer)
                BxDolSession::getInstance()->setValue('join-referrer', $sJoinReferrer);
        }

        $aTmplVarsAuth = [];
        if(!isset($aParams['no_auth_buttons']) || false === (bool)$aParams['no_auth_buttons'])
            $aTmplVarsAuth['content'] = BxDolService::call('system', 'member_auth_code', array(), 'TemplServiceLogin');

        $aTmplVarsForm = [];
        if(!(bool)getParam('sys_account_disable_join_form'))
            $aTmplVarsForm['content'] = $this->_oAccountForms->createAccountForm($aParams);

        $aTmplVarsLogin = [];
        if(!isset($aParams['no_login_text']) || false === (bool)$aParams['no_login_text'])
            $aTmplVarsLogin['url'] = bx_absolute_url($oPemalink->permalink('page.php?i=login'));
        
        return BxDolTemplate::getInstance()->parseHtmlByName('block_join.html', [
            'bx_if:show_auth' => [
                'condition' => !empty($aTmplVarsAuth),
                'content' => $aTmplVarsAuth
            ],
            'bx_if:show_form' => [
                'condition' => !empty($aTmplVarsForm),
                'content' => $aTmplVarsForm,
            ],
            'bx_if:show_login' => [
                'condition' => !empty($aTmplVarsLogin),
                'content' => $aTmplVarsLogin
            ],
            'agreement' => _t('_sys_form_account_input_agreement_value', bx_absolute_url($oPemalink->permalink('page.php?i=terms')), bx_absolute_url($oPemalink->permalink('page.php?i=privacy'))),
        ]);
    }

    public function serviceAccountSettingsEmail ($iAccountId = false)
    {
        if (false === $iAccountId)
            $iAccountId = getLoggedId();
        return $this->_oAccountForms->editAccountEmailSettingsForm($iAccountId);
    }

    public function serviceAccountSettingsPassword ($iAccountId = false)
    {
        if (false === $iAccountId)
            $iAccountId = getLoggedId();
        return $this->_oAccountForms->editAccountPasswordSettingsForm($iAccountId);
    }

    public function serviceAccountSettingsInfo ($iAccountId = false)
    {
        if (false === $iAccountId)
            $iAccountId = getLoggedId();
        return $this->_oAccountForms->editAccountInfoForm($iAccountId);
    }

    public function serviceAccountSettingsDelAccount ($iAccountId = false)
    {
        if (!$iAccountId)
        	$iAccountId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iAccountId)
            $iAccountId = getLoggedId();

        return $this->_oAccountForms->deleteAccountForm($iAccountId);
    }

    /**
     * Display unsubscribe from newsletters form
     */
    public function serviceUnsubscribeNews()
    {
        return $this->_unsubscribeForm('sys_unsubscribe_news');
    }

    /**
     * Display unsubscribe from notifications form
     */
    public function serviceUnsubscribeNotifications()
    {
        return $this->_unsubscribeForm('sys_unsubscribe_updates');
    }
    
    /**
     * Display confirmation statuses list
     */
    public function serviceGetConfirmationTypes()
    {
        $aResult = array();
        $aChoices = array(BX_ACCOUNT_CONFIRMATION_NONE, BX_ACCOUNT_CONFIRMATION_EMAIL, BX_ACCOUNT_CONFIRMATION_PHONE, BX_ACCOUNT_CONFIRMATION_EMAIL_PHONE, BX_ACCOUNT_CONFIRMATION_EMAIL_OR_PHONE);
        foreach($aChoices as $sChoice)
            $aResult[$sChoice] = _t('_sys_acc_confirmation_type_' . $sChoice);
        
        return $aResult;
    }

    protected function _unsubscribeForm($sDisplay)
    {
        $iAccountId = bx_process_input(bx_get('id'));
        $sCode = bx_process_input(bx_get('code'));

        // validate input params

        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (!$oAccount)
            return MsgBox(_t('_sys_txt_unsubscribe_wrong_link'));

        if ($sCode != $oAccount->getEmailHash())
            return MsgBox(_t('_sys_txt_unsubscribe_wrong_link'));

        $oForm = BxDolForm::getObjectInstance('sys_unsubscribe', $sDisplay);
        if (!$oForm)
            return MsgBox(_t('_sys_txt_unsubscribe_error_occured'));

        // show form

        $aAccountInfo = $oAccount->getInfo();
        $aAccountInfo['code'] = $sCode;
        $oForm->initChecker($aAccountInfo);

        if ($oForm->isSubmittedAndValid()) {

            if (!$oForm->update($oAccount->id()))
                return MsgBox(_t('_sys_txt_unsubscribe_error_occured'));

            return MsgBox(_t('_sys_txt_unsubscribe_email_settings_update_success'));
        }

        return '<div class="bx-def-padding-bottom">' . _t("_sys_txt_unsubscribe_info", $aAccountInfo['email']) . '</div>' . $oForm->getCode();
    }

    /**
     * Display email confirmation form, if confirmation code is provided then try to confirm profile
     */
    public function serviceEmailConfirmation($sMsg = false)
    {
        // if user is logged in and email is confirmed then just display a message
        if (isLogged() && !bx_get('code')) {
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount->isConfirmedEmail())
                return MsgBox(_t("_sys_txt_confirm_email_already_confirmed"));
        }

        // if confirmation code is provided in the URL - perform email confirmation right away
        if (bx_get('code') !== false)
            return $this->confirmEmail(bx_process_input(bx_get('code')));

        // if user requested to resend verification letter then send letter and display message
        if (bx_process_input(bx_get('resend')) && isLogged()) {
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount->sendConfirmationEmail())
                $sMsg = _t('_sys_txt_confirm_email_sent');
            else
                $sMsg = _t('_sys_txt_confirm_email_sent_failed');
            return MsgBox($sMsg);
        }

        // show and process code verification form

        $oForm = BxDolForm::getObjectInstance('sys_confirm_email', 'sys_confirm_email');
        if (!$oForm)
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) {

            $oKey = BxDolKey::getInstance();
            if (!$oKey)
                return MsgBox(_t("_sys_txt_confirm_email_error_occured"));
            else
                return $this->confirmEmail(trim($oForm->getCleanValue('code')));

        }

        return '<div class="bx-def-padding-sec-bottom">' . _t("_sys_txt_confirm_email_desc") . '</div>' . $oForm->getCode();
    }
    
    /**
     * Display phone confirmation forms, if confirmation code is provided then try to confirm profile
     */
    public function servicePhoneConfirmation($sMsg = false)
    {
        // if user is logged in and phone is confirmed then just display a message
        if (isLogged()) {
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount->isConfirmedPhone())
                return MsgBox(_t("_sys_txt_confirm_phone_already_confirmed"));
            
            $iStep = 1;
            $a = $oAccount->getInfo();
            $sPhoneNumber = $a['phone'];
            
            $oSession = BxDolSession::getInstance();           
            if ($sPhoneNumber != "" && $oSession->isValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE)){
                 $iStep = 2;
            }
        
            if (bx_get('step'))
                $iStep = (int)bx_get('step');
        
            // show and process phone set form
            if ($iStep == 1){
                $oForm = BxDolForm::getObjectInstance('sys_confirm_phone', 'sys_confirm_phone_set_phone');
                if (!$oForm)
                    return MsgBox(_t("_sys_txt_confirm_phone_set_phone_error_occured"));
            
                $oForm->initChecker(array('phone' => $sPhoneNumber));
                if ($oForm->isSubmittedAndValid()) {
                    $oAccount->updatePhone(trim($oForm->getCleanValue('phone')));
                    $sActivationCode = rand(1000, 9999);
                    $sActivationText =_t('_sys_txt_confirm_phone_sms_text', $sActivationCode);
                    $ret = null;
                    bx_alert('account', 'before_confirm_phone_send_sms', $oAccount->id(), bx_get_logged_profile_id(), array('phone_number' => $sPhoneNumber, 'sms_text' => $sActivationText, 'override_result' => &$ret));
                    if ($ret === null) 
                    {
                        $oTwilio = BxDolTwilio::getInstance();
                        if(!$oTwilio->sendSms($sPhoneNumber,  $sActivationText)){
                            return MsgBox(_t("_sys_txt_confirm_phone_send_sms_error_occured"));
                        }
                    }

                    $oSession->setValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE, $sActivationCode);
                    header('Location: ' . bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-phone')));
                } 
                return '<div class="bx-def-padding-sec-bottom">' . _t("_sys_txt_confirm_phone_set_phone_desc_set_phone") . '</div>' .$oForm->getCode();
            }
        
            if ($iStep == 2){
                $oForm = BxDolForm::getObjectInstance('sys_confirm_phone', 'sys_confirm_phone_confirmation');
                if (!$oForm)
                    return MsgBox(_t("_sys_txt_confirm_phone_set_phone_confirmation_occured"));
            
                $oForm->initChecker();
            
                if ($oForm->isSubmittedAndValid()) {
                    $oAccount->updatePhoneConfirmed(true);
                    $oSession->unsetValue(BX_ACCOUNT_SESSION_KEY_FOR_PHONE_ACTIVATEION_CODE);
                    return MsgBox(_t("_sys_txt_confirm_phone_confirm_success"));
                } 
                return '<div class="bx-def-padding-sec-bottom">' . _t("_sys_txt_confirm_phone_set_phone_desc_phone_confirmation") . '</div>' .$oForm->getCode();
            }
        }
    }

    /**
     * Perform email confirmation
     */
    public function confirmEmail($sKey)
    {
        // check if key exists
        $oKey = BxDolKey::getInstance();
        if (!$oKey || !$oKey->isKeyExists($sKey))
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        // check if key data exists
        $aData = $oKey->getKeyData($sKey);
        if (!isset($aData['account_id']))
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        // check if account exists
        $oAccount = BxDolAccount::getInstance($aData['account_id']);
        if (!$oAccount)
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        // remove key
        $oKey->removeKey($sKey);

        // confirm email
        if (!$oAccount->updateEmailConfirmed(true))
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        // login to user's account automatically
        bx_login($aData['account_id']);

        // redirect with success message
        $sUrl = getParam('sys_redirect_after_email_confirmation');

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_TRANSITION);
        $oTemplate->setPageHeader (_t('_sys_txt_confirm_email_success'));
        $oTemplate->setPageContent ('page_main_code', MsgBox(_t('_sys_txt_confirm_email_success')));
        $oTemplate->setPageContent ('url_relocate', bx_absolute_url(BxDolPermalinks::getInstance()->permalink($sUrl)));

        BxDolTemplate::getInstance()->getPageCode();
        exit;
    }

    public function serviceGetOptionsResetPasswordRedirect()
    {
        $aResult = array();

        $aChoices = array('home', 'profile', 'profile_edit', 'custom');
        foreach($aChoices as $sChoice) 
            $aResult[] = array('key' => $sChoice, 'value' => _t('_adm_stg_cpt_option_value_sys_account_reset_password_redirect_' . $sChoice));

        return $aResult;
    }

    public function serviceGetOptionsSwitchToProfileRedirect()
    {
        $aResult = array();

        $aChoices = array('back', 'home', 'profile', 'custom');
        foreach($aChoices as $sChoice) 
            $aResult[] = array('key' => $sChoice, 'value' => _t('_adm_stg_cpt_option_value_sys_account_switch_to_profile_redirect_' . $sChoice));

        return $aResult;
    }

    public function serviceGetOptionsPruningInterval()
    {
        $aResult = array();

        $aChoices = array('no', 'no_login_delete', 'no_login_suspend', 'no_confirm_delete', 'no_profile_delete');
        foreach($aChoices as $sChoice) 
            $aResult[] = array('key' => $sChoice, 'value' => _t('_adm_stg_cpt_option_value_sys_account_pruning_' . $sChoice));

        return $aResult;
    }
            
    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-account Account
     * @subsubsection bx_system_general-forgot_password forgot_password
     * 
     * @code bx_srv('system', 'forgot_password', [], 'TemplServiceAccount'); @endcode
     * @code {{~system:forgot_password:TemplServiceAccount~}} @endcode
     * 
     * Display forgot password form, 
     * if reset password key is provided then reset password
     * 
     * @see BxBaseServiceAccount::serviceForgotPassword
     */
    /** 
     * @ref bx_system_general-forgot_password "forgot_password"
     */
    public function serviceForgotPassword()
    {
        if(isLogged()){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }

        if (bx_get('key') !== false)
            return $this->resetPassword();

        $oForm = BxDolForm::getObjectInstance('sys_forgot_password', 'sys_forgot_password');
        if (!$oForm)
            return '';

        $bNeedCheckEmailAndPhone = true;
        $sCaptionKey = "_sys_txt_forgot_pasword_by_both_desc";
        if (BxDolAccount::isNeedConfirmPhone()){
            if (!BxDolAccount::isNeedConfirmEmail()){
                unset($oForm->aInputs['email']);
                $sCaptionKey = "_sys_txt_forgot_pasword_by_phone_desc";
                $oForm->aInputs['phone']['checker']['func'] = 'PhoneExist';
                $bNeedCheckEmailAndPhone = false;
            }
        }
        else{
            unset($oForm->aInputs['phone']);
            $sCaptionKey = "_sys_txt_forgot_pasword_desc";
            $oForm->aInputs['email']['checker']['func'] = 'EmailExist';
            $bNeedCheckEmailAndPhone = false;
        }

        if ((BxDolAccount::isNeedConfirmPhone() && BxDolAccount::isNeedConfirmEmail())){
            if (isset($oForm->aInputs['phone']))
                $oForm->aInputs['phone']['required'] = false;
            if (isset($oForm->aInputs['email']))
                $oForm->aInputs['email']['required'] = false;
        }

        $oForm->initChecker();

        $bShowErrorEmptyBoth = false;
        if ($oForm->isSubmitted() && $bNeedCheckEmailAndPhone && $oForm->getCleanValue('phone') == '' && $oForm->getCleanValue('email') == ''){
            $bShowErrorEmptyBoth = true;
            $oForm->setValid(false);
        }

        if ($oForm->isSubmittedAndValid()) {
            $sEmail = $oForm->getCleanValue('email');
            $sPhone = $oForm->getCleanValue('phone');

            if (isset($oForm->aInputs['email']) &&  $sEmail != '') {
                $oAccount = BxDolAccount::getInstance($this->_oAccountQuery->getIdByEmail($sEmail));

                if($oAccount && $oAccount->sendResetPasswordEmail())
                    $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_check_email"));
                else
                    $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_email_send_failed"));

                $sForm = '';
            }

            if (isset($oForm->aInputs['phone']) &&  $sPhone != '') {   
                $iAccountId = $this->_oAccountQuery->getIdByPhone($sPhone);
                $aAccountInfo = $this->_oAccountQuery->getInfoById($iAccountId);

                $sKey = bx_get_reset_password_key($aAccountInfo['email']);
                $sForgotPasswordUrl = bx_get_reset_password_link_by_key($sKey); 

                $sSmsText = _t('_sys_txt_forgot_pasword_sms_text', $sForgotPasswordUrl);

                $mixedOverrideResult = null;
                bx_alert('account', 'before_forgot_password_send_sms', $aAccountInfo['id'], false, array('phone_number' => &$sPhone, 'sms_text' => &$sSmsText, 'override_result' => &$mixedOverrideResult));
                if ($mixedOverrideResult === null) {
                    $oTwilio = BxDolTwilio::getInstance();
                    if($oTwilio->sendSms($sPhone,  $sSmsText))
                        $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_check_phone"));
                    else
                        $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_error_occured"));
                }

                $sForm = '';
            }
        } 
        else {
            $sResultMsg = _t($sCaptionKey);
            $sForm = $oForm->getCode();
        }

        return '<div class="bx-def-padding-sec-bottom">' . $sResultMsg . '</div>' . $sForm . ($bShowErrorEmptyBoth ? '<div class="bx-form-warn">' . _t("_sys_form_forgot_password_phone_and_email_empty_error") . '</div>' : '');
    }

    /**
     * @page service Service Calls
     * @section bx_system_general System Services 
     * @subsection bx_system_general-account Account
     * @subsubsection bx_system_general-switch_profile switch_profile
     * 
     * @code bx_srv('system', 'switch_profile', [123], 'TemplServiceAccount'); @endcode
     * @code {{~system:switch_profile:TemplServiceAccount:[123]~}} @endcode
     * 
     * Switch current user profile. 
     * User can switch between profiles if multiple profiles are created.
     * Also it's possible to switch to Organization profile if user is admin of Organization, 
     * or other module which can `actAsProfile`.
     * Admin can switch to any prifile by default
     *
     * @param $iSwitchToProfileId profile ID to switch to
     * @return true on success or error message otherwise, so make sue to make strict (===) comparision
     * 
     * @see BxBaseServiceAccount::serviceSwitchProfile
     */
    /** 
     * @ref bx_system_general-switch_profile "switch_profile"
     */
    public function serviceSwitchProfile($iSwitchToProfileId)
    {
        $oProfile = BxDolProfile::getInstance($iSwitchToProfileId);
        if (!$oProfile) {
            return _t('_sys_txt_error_occured');
        }

        $iViewerAccountId = getLoggedId();
        $iSwitchToAccountId = $oProfile->getAccountId();
        
        $iViewerProfileId = bx_get_logged_profile_id();
        $aCheck = checkActionModule($iViewerProfileId, 'switch to any profile', 'system', false);
        $bAllowSwitchToAnyProfile = $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED;

        $bCanSwitch = ($iSwitchToAccountId == $iViewerAccountId || $bAllowSwitchToAnyProfile);
        bx_alert('account', 'check_switch_context', $iSwitchToAccountId, $iViewerProfileId, array('switch_to_profile' => $iSwitchToProfileId, 'viewer_account' => $iViewerAccountId, 'override_result' => &$bCanSwitch));

        if (!$bCanSwitch) {
            return isset($aCheck[CHECK_ACTION_MESSAGE]) ? $aCheck[CHECK_ACTION_MESSAGE] : _t('_sys_txt_error_occured'); 
        } 

        $oAccount = BxDolAccount::getInstance();
        if (!$oAccount->updateProfileContext($iSwitchToProfileId)) {
            return _t('_sys_txt_error_occured');
        }

        checkActionModule($iViewerProfileId, 'switch to any profile', 'system', true);
        return true;
    }

    /**
     * Reset password procedure
     */
    public function resetPassword()
    {
        $sKey = bx_process_input(bx_get('key'));

        $oForm = BxDolForm::getObjectInstance('sys_forgot_password', 'sys_forgot_password_reset');
        if(!empty($sKey) && isset($oForm->aInputs['key']))
            $oForm->aInputs['key']['value'] = $sKey;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sErrorUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password'));

            // check if key exists
            $oKey = BxDolKey::getInstance();
            $sKey = $oForm->getCleanValue('key');
            if(!$oKey || !$oKey->isKeyExists($sKey))
                return MsgBox(_t('_sys_txt_reset_pasword_error_invalid_key', $sErrorUrl));

            // check if key data exists
            $aKeyData = $oKey->getKeyData($sKey);
            if(empty($aKeyData['email']))
                return MsgBox(_t('_sys_txt_reset_pasword_error_invalid_key', $sErrorUrl));

            // check if account with such email exists
            $iAccountId = $this->_oAccountQuery->getIdByEmail($aKeyData['email']);
            if(empty($iAccountId))
                return MsgBox(_t('_sys_txt_reset_pasword_error_not_found', $sErrorUrl));;

            $sPassword = $oForm->getCleanValue('password');
            $oAccount = BxDolAccount::getInstance($iAccountId);
            if (!$oAccount || !$oAccount->updatePassword($sPassword))
                return MsgBox(_t('_sys_txt_reset_pasword_error_occured', $sErrorUrl));

            $this->_oAccountQuery->unlockAccount($iAccountId);
            $oKey->removeKey($sKey);

            return MsgBox(_t('_sys_txt_reset_pasword_success'), 3) . bx_srv('system', 'login_form_only', array('', bx_get_reset_password_redirect($iAccountId)), 'TemplServiceLogin');
        }

        return $oForm->getCode();
    }

    /**
     * Generate new password.
     */
    public function generateUserNewPwd($iAccountId)
    {
        $sPwd = genRndPwd(8, false);
        $sSalt = genRndSalt();
        $sPasswordHash = encryptUserPwd($sPwd, $sSalt);
        
        $oAccount = BxDolAccount::getInstance($iAccountId);
        $iPasswordExpired = $oAccount->getPasswordExpiredDateByAccount($iAccountId);
        
        $this->_oAccountQuery->updatePassword($sPasswordHash, $sSalt, $iAccountId, $iPasswordExpired);

        bx_alert('account', 'edited', $iAccountId, $iAccountId, array('action' => 'forgot_password'));

        return $sPwd;
    }

}

/** @} */
