<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolAccountQuery');
bx_import('BxTemplAccountForms');

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

    public function serviceCreateAccountForm ()
    {
        return $this->_oAccountForms->createAccountForm();
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
        if (false === $iAccountId)
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

    protected function _unsubscribeForm($sDisplay)
    {
        $iAccountId = bx_process_input(bx_get('id'));
        $sCode = bx_process_input(bx_get('code'));

        // validate input params

        bx_import('BxDolAccount');
        $oAccount = BxDolAccount::getInstance($iAccountId);
        if (!$oAccount)
            return MsgBox(_t('_sys_txt_unsubscribe_wrong_link'));

        if ($sCode != $oAccount->getEmailHash())
            return MsgBox(_t('_sys_txt_unsubscribe_wrong_link'));

        bx_import('BxDolForm');
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
            bx_import('BxDolAccount');
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount->isConfirmed())
                return MsgBox(_t("_sys_txt_confirm_email_already_confirmed"));
        }

        // if confirmation code is provided in the URL - perform email confirmation right away
        if (!empty($_GET['code']))
            return $this->confirmEmail(bx_process_input($_GET['code']));

        // if user requested to resend verification letter then send letter and display message
        if (bx_process_input(bx_get('resend')) && isLogged()) {
            bx_import('BxDolAccount');
            $oAccount = BxDolAccount::getInstance();
            if ($oAccount->sendConfirmationEmail())
                $sMsg = _t('_sys_txt_confirm_email_sent');
            else
                $sMsg = _t('_sys_txt_confirm_email_sent_failed');
            return MsgBox($sMsg);
        }

        // show and process code verification form

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('sys_confirm_email', 'sys_confirm_email');
        if (!$oForm)
            return MsgBox(_t("_sys_txt_confirm_email_error_occured"));

        $oForm->initChecker();

        if ($oForm->isSubmittedAndValid()) {

            bx_import('BxDolKey');
            $oKey = BxDolKey::getInstance();
            if (!$oKey)
                return MsgBox(_t("_sys_txt_confirm_email_error_occured"));
            else
                return $this->confirmEmail(trim($oForm->getCleanValue('code')));

        }

        return '<div class="bx-def-padding-sec-bottom">' . _t("_sys_txt_confirm_email_desc") . '</div>' . $oForm->getCode();
    }

    /**
     * Perform email confirmation
     */
    public function confirmEmail($sKey)
    {
        // check if key exists
        bx_import('BxDolKey');
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
        bx_import('BxDolPermalinks');
        bx_import('BxDolTemplate');
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_TRANSITION);
        $oTemplate->setPageHeader (_t('_sys_txt_confirm_email_success'));
        $oTemplate->setPageContent ('page_main_code', MsgBox(_t('_sys_txt_confirm_email_success')));
        $oTemplate->setPageContent ('url_relocate', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=account-settings-info'));

        BxDolTemplate::getInstance()->getPageCode();
        exit;
    }

    /**
     * Display forgot password form, if reset password key is provided then reset password
     */
    public function serviceForgotPassword()
    {
        if (bx_get('key'))
            return $this->resetPassword();

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('sys_forgot_password', 'sys_forgot_password');
        if (!$oForm)
            return '';

        $oForm->initChecker();

        if ( $oForm->isSubmittedAndValid() ) {

            bx_import('BxDolKey');
            $oKey = BxDolKey::getInstance();
            if (!$oKey) {

                $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_error_occured"));

            } else {

                $sEmail = $oForm->getCleanValue('email');
                $iAccountId = $this->_oAccountQuery->getIdByEmail($sEmail);

                bx_import('BxDolPermalinks');
                $aPlus['key'] = $oKey->getNewKey(array('email' => $sEmail));
                $aPlus['forgot_password_url'] = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password') . '&key=' . $aPlus['key'];

                bx_import('BxDolEmailTemplates');
                $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('t_Forgot', $aPlus, $iAccountId);

                if ($aTemplate && sendMail($sEmail, $aTemplate['Subject'], $aTemplate['Body'], 0, $aPlus, BX_EMAIL_SYSTEM))
                    $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_check_email"));
                else
                    $sResultMsg = MsgBox(_t("_sys_txt_forgot_pasword_email_send_failed"));

                $sForm = '';

            }

        } else {

            $sResultMsg = _t("_sys_txt_forgot_pasword_desc");
            $sForm = $oForm->getCode();

        }

        return '<div class="bx-def-padding-sec-bottom">' . $sResultMsg . '</div>' . $sForm;
    }

    /**
     * Reset password procedure
     */
    public function resetPassword()
    {
        $sKey = bx_process_input(bx_get('key'));

        // get link to forgot password page for error message
        bx_import('BxDolPermalinks');
        $sUrlForgotPassword = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password');

        // check if key exists
        bx_import('BxDolKey');
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

        bx_import('BxDolEmailTemplates');
        $aTemplate = BxDolEmailTemplates::getInstance() -> parseTemplate('t_PasswordReset', $aPlus, $iAccountId);

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
        $sPwd = genRndPwd();
        $sSalt = genRndSalt();
        $sPasswordHash = encryptUserPwd($sPwd, $sSalt);

        $this->_oAccountQuery->updatePassword($sPasswordHash, $sSalt, $iAccountId);

        bx_alert('account', 'edit', $iAccountId, $iAccountId, array('action' => 'forgot_password'));

        return $sPwd;
    }

}

/** @} */
