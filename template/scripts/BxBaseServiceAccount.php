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

    public function serviceCreateAccountForm ($aParams = array())
    {   
        if(isLogged()){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }
        
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

	    $sLoginText = '';
        if (!isset($aParams['no_login_text']) || false === (bool)$aParams['no_login_text'])
            $sLoginText = '<hr class="bx-def-hr bx-def-margin-sec-topbottom" /><div>' . _t('_sys_txt_join_description', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=login')) . '</div>';
        
        $sAuth = '';
        if (!isset($aParams['no_auth_buttons']) || false === (bool)$aParams['no_auth_buttons'])
            $sAuth = BxDolService::call('system', 'member_auth_code', array(), 'TemplServiceLogin');

        return $sAuth . $this->_oAccountForms->createAccountForm($aParams) . $sLoginText;
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
        $aChoices = array(BX_ACCOUNT_CONFIRMATION_NONE, BX_ACCOUNT_CONFIRMATION_EMAIL, BX_ACCOUNT_CONFIRMATION_PHONE, BX_ACCOUNT_CONFIRMATION_EMAIL_PHONE);
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
        if (isLogged()) {
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
                    header('Location: ' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=confirm-phone'));
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
        $oTemplate->setPageContent ('url_relocate', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sUrl));

        BxDolTemplate::getInstance()->getPageCode();
        exit;
    }

    /**
     * Display forgot password form, if reset password key is provided then reset password
     */
    public function serviceForgotPassword()
    {
        if(isLogged()){
            header('Location: ' . BX_DOL_URL_ROOT);
            exit;
        }
        
        if (bx_get('key'))
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
            $oKey = BxDolKey::getInstance();
            if (!$oKey) {
                $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_error_occured"));
            } 
            else {
                $sEmail = $oForm->getCleanValue('email');
                $sPhone = $oForm->getCleanValue('phone');
                
                if (isset($oForm->aInputs['email']) &&  $sEmail != '')
                {
                    $iAccountId = $this->_oAccountQuery->getIdByEmail($sEmail);

                    $aPlus['key'] = $oKey->getNewKey(array('email' => $sEmail));
                    $aPlus['forgot_password_url'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password', array('key' => $aPlus['key']));

                    $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('t_Forgot', $aPlus, $iAccountId);

                    if ($aTemplate && sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, $aPlus, BX_EMAIL_SYSTEM))
                        $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_check_email"));
                    else
                        $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_email_send_failed"));

                    $sForm = '';
                }
                
                if (isset($oForm->aInputs['phone']) &&  $sPhone != ''){   
                    $iAccountId = $this->_oAccountQuery->getIdByPhone($sPhone);
                    $aAccountInfo = $this->_oAccountQuery->getInfoById($iAccountId);
                    
                    $aPlus['key'] = $oKey->getNewKey(array('email' => $aAccountInfo['email']));
                    $aPlus['forgot_password_url'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password', array('key' => $aPlus['key']));

                    $sSmsText = _t('_sys_txt_forgot_pasword_sms_text', $aPlus['forgot_password_url']);
                    
                    $ret = null;
                    bx_alert('account', 'before_forgot_password_send_sms', $aAccountInfo['id'], false, array('phone_number' => $sPhone, 'sms_text' => $sSmsText, 'override_result' => &$ret));
                    if ($ret === null) 
                    {
                        $oTwilio = BxDolTwilio::getInstance();
                        if($oTwilio->sendSms($sPhone,  $sSmsText))
                            $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_check_phone"));
                        else
                            $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_error_occured"));
                    }
                    $sForm = '';
                }
            }
        } 
        else {
            $sResultMsg = _t($sCaptionKey);
            $sForm = $oForm->getCode();
        }
        return '<div class="bx-def-padding-sec-bottom">' . $sResultMsg . '</div>' . $sForm . ($bShowErrorEmptyBoth ? '<div class="bx-form-warn">' . _t("_sys_form_forgot_password_phone_and_email_empty_error") . '</div>' : '');
    }

    /**
     * Reset password procedure
     */
    public function resetPassword()
    {
        $sKey = bx_process_input(bx_get('key'));

        // get link to forgot password page for error message
        $sUrlForgotPassword = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password');

        // check if key exists
        $oKey = BxDolKey::getInstance();
        if (!$oKey || !$oKey->isKeyExists($sKey))
            return _t("_sys_txt_reset_pasword_error_occured", $sUrlForgotPassword);

        // check if key data exists
        $aData = $oKey->getKeyData($sKey);
        if (!isset($aData['email']))
            return _t("_sys_txt_reset_pasword_error_occured", $sUrlForgotPassword);

        // check if account with such email exists
        $iAccountId = $this->_oAccountQuery->getIdByEmail($aData['email']);
        if (!$iAccountId)
            return _t("_sys_txt_reset_pasword_error_occured", $sUrlForgotPassword);

        // generate new password
        $sPassword = $this->generateUserNewPwd($iAccountId);

        // remove key
        $oKey->removeKey($sKey);

        // send email with new password and display result message
        $aPlus = array ('password' => $sPassword);

        $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('t_PasswordReset', $aPlus, $iAccountId);

		$oAccountQuery = BxDolAccountQuery::getInstance();
		$oAccountQuery->unlockAccount($iAccountId);
		
        if ($aTemplate && sendMail($aData['email'], $aTemplate['Subject'], $aTemplate['Body'], 0, $aPlus, BX_EMAIL_SYSTEM))
            return _t("_sys_txt_reset_pasword_email_sent", $sPassword);
        else
            return _t("_sys_txt_reset_pasword_email_send_failed", $sPassword);
    }

    /**
     * Generate new password.
     */
    protected function generateUserNewPwd($iAccountId)
    {
        $sPwd = genRndPwd(8, false);
        $sSalt = genRndSalt();
        $sPasswordHash = encryptUserPwd($sPwd, $sSalt);

        $this->_oAccountQuery->updatePassword($sPasswordHash, $sSalt, $iAccountId);

        bx_alert('account', 'edited', $iAccountId, $iAccountId, array('action' => 'forgot_password'));

        return $sPwd;
    }

}

/** @} */
