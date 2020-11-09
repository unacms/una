<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');

if (isset($_POST['ID'])) { // login form is submitted

    $oForm = BxDolForm::getObjectInstance('sys_login', 'sys_login');

    bx_alert('account', 'before_login', 0, 0, array('form' => $oForm));

    $oForm->initChecker();
    $oForm->setRole(bx_get('role'));
    $bLoginSuccess = $oForm->isSubmittedAndValid();

    $bAjxMode = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
    if ($bAjxMode) {

        if ($bLoginSuccess) {
            $s = 'OK';
            $oAccount = BxDolAccount::getInstance(trim($oForm->getCleanValue('ID')));
            $aAccount = bx_login($oAccount->id(), ($oForm->getCleanValue('rememberMe') ? true : false));
        }
        else {
            $s = $oForm->getLoginError();
        }

        if (isset($_SERVER['HTTP_ACCEPT'])) {
            if (false !== strpos($_SERVER['HTTP_ACCEPT'], 'application/json') || false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript')) {
                header('Content-type: application/json; charset=utf-8');
                echo json_encode(['res' => $s, 'form' => $oForm->getCode()]);
                exit;
            }
        }

        header('Content-type: text/html; charset=utf-8');
        echo $s;
        exit;

    } 
    elseif ($bLoginSuccess) {
        $sId = trim($oForm->getCleanValue('ID'));
        $oAccount = BxDolAccount::getInstance($sId);
        $aAccountInfo = $oAccount->getInfo();
        if (
            (getParam('sys_account_activation_2fa_enable') == 'on' && getParam('sys_twilio_gate_sid') != '' && getParam('sys_twilio_gate_token') != '' && getParam('sys_twilio_gate_from_number') != '') 
            && (getParam('sys_account_activation_2fa_lifetime') == 0 || (time() - $aAccountInfo['logged'] > getParam('sys_account_activation_2fa_lifetime')))){
            $oSession = BxDolSession::getInstance();
            $oSession->setValue(BX_ACCOUNT_SESSION_KEY_FOR_2FA_LOGIN_ACCOUNT_ID, trim($oForm->getCleanValue('ID')));
            $oSession->setValue(BX_ACCOUNT_SESSION_KEY_FOR_2FA_LOGIN_IS_REMEMBER, ($oForm->getCleanValue('rememberMe') ? true : false));
            header('Location: ' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=login-step2'));
        }
        else{
    	   
            $aAccount = bx_login($oAccount->id(), ($oForm->getCleanValue('rememberMe') ? true : false));

            $sUrlRelocate = $oForm->getCleanValue('relocate');
            if (!$sUrlRelocate || 0 !== strncmp($sUrlRelocate, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)))
                $sUrlRelocate = BX_DOL_ROLE_ADMIN == $oForm->getRole() ? BX_DOL_URL_STUDIO . 'launcher.php' : BX_DOL_URL_ROOT . 'member.php';

            bx_alert('account', 'login_after', $oAccount->id(),  false, array(
                'account' => $aAccount,
                'url_relocate' => &$sUrlRelocate               
            ));

            BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_TRANSITION);
            BxDolTemplate::getInstance()->setPageHeader (_t('_Please Wait'));
            BxDolTemplate::getInstance()->setPageContent ('page_main_code', MsgBox(_t('_Please Wait')));
            BxDolTemplate::getInstance()->setPageContent ('url_relocate', bx_html_attribute($sUrlRelocate, BX_ESCAPE_STR_QUOTE));

            BxDolTemplate::getInstance()->getPageCode();
        exit;
        }
    }

}

bx_require_authentication();

header('Location: ' . BX_DOL_URL_ROOT);

/** @} */
