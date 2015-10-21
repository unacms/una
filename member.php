<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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

        header( 'Content-type: text/html; charset=utf-8' );
        echo $bLoginSuccess ? 'OK' : $oForm->getLoginError();
        exit;

    } elseif ($bLoginSuccess) {
    	$sId = trim($oForm->getCleanValue('ID'));

        $oAccount = BxDolAccount::getInstance($sId);
        $aAccount = bx_login($oAccount->id(), ($oForm->getCleanValue('rememberMe') ? true : false));

        $sUrlRelocate = $oForm->getCleanValue('relocate');
        if (!$sUrlRelocate || 0 != strncmp($sUrlRelocate, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)))
            $sUrlRelocate = BX_DOL_ROLE_ADMIN == $oForm->getRole() ? BX_DOL_URL_STUDIO . 'launcher.php' : BX_DOL_URL_ROOT . 'member.php';

        BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_TRANSITION);
        BxDolTemplate::getInstance()->setPageHeader (_t('_Please Wait'));
        BxDolTemplate::getInstance()->setPageContent ('page_main_code', MsgBox(_t('_Please Wait')));
        BxDolTemplate::getInstance()->setPageContent ('url_relocate', bx_html_attribute($sUrlRelocate, BX_ESCAPE_STR_QUOTE));

        BxDolTemplate::getInstance()->getPageCode();
        exit;
    }

}

bx_require_authentication();

header('Location: ' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=account-profile-switcher'));

/** @} */
