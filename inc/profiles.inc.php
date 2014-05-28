<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

/**
 * It is needed to check whether user is logged in or not
 */
function isLogged() {
    return getLoggedId() != 0;
}

/**
 * It returns logged in account id
 */
function getLoggedId() {
    return isset($_COOKIE['memberID']) && (!empty($GLOBALS['logged']['member']) || !empty($GLOBALS['logged']['admin'])) ? (int)$_COOKIE['memberID'] : 0;
}

/**
 * It returns logged in account password
 */
function getLoggedPassword() {
    return isset($_COOKIE['memberPassword']) && ($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) ? $_COOKIE['memberPassword'] : '';
}

/**
 * It checks if account is member.
 */
function isMember($iId = 0) {
    return isRole(BX_DOL_ROLE_MEMBER, $iId);
}

if (!function_exists("isAdmin")) {
    /**
     * It checks if account is admin.
     */
    function isAdmin($iId = 0) {
        if (!$iId && isset($GLOBALS['logged']['admin']) && $GLOBALS['logged']['admin']) // easier check for currently logged in user
            return true;
        return isRole(BX_DOL_ROLE_ADMIN, $iId);
    }
}

/**
 * It checks account's role
 */
function isRole($iRole, $iId = 0) {

    if (!(int)$iId)
        $iId = getLoggedId();

    bx_import('BxDolAccount');
    $oAccount = BxDolAccount::getInstance($iId);
    if (!$oAccount)
        return false;

    $aAccountInfo = $oAccount->getInfo();

    if (!$aAccountInfo)
        return false;

    if (!((int)$aAccountInfo['role'] & $iRole))
        return false;

    return true;
}

function bx_login($iId, $bRememberMe = false) {
    bx_import('BxDolAccountQuery');
    $oAccountQuery = BxDolAccountQuery::getInstance();

    $sPassword = $oAccountQuery->getPassword($iId);
    if (!$sPassword)
        return false;

    $aUrl = parse_url(BX_DOL_URL_ROOT);
    $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';
    $sHost = '';
    $iCookieTime = $bRememberMe ? time() + 24*60*60*30 : 0;
    setcookie("memberID", $iId, $iCookieTime, $sPath, $sHost);
    $_COOKIE['memberID'] = $iId;
    setcookie("memberPassword", $sPassword, $iCookieTime, $sPath, $sHost, false, true /* http only */);
    $_COOKIE['memberPassword'] = $sPassword;

    bx_import('BxDolSession');
	BxDolSession::getInstance()->setUserId($iId);

    $oAccountQuery->updateLoggedIn($iId);

    bx_alert('account', 'login',  $iId);

    return $oAccountQuery->getInfoById($iId);
}

function bx_logout($bNotify = true) {
    if ($bNotify && isMember())
        bx_alert('account', 'logout', (int)$_COOKIE['memberID']);

    $aUrl = parse_url(BX_DOL_URL_ROOT);
    $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';

    setcookie('memberID', '', time() - 96 * 3600, $sPath);
    setcookie('memberPassword', '', time() - 96 * 3600, $sPath);

    unset($_COOKIE['memberID']);
    unset($_COOKIE['memberPassword']);

    bx_import('BxDolSession');
	BxDolSession::getInstance()->destroy();
}

function check_logged() {

    $aAccTypes = array(
       BX_DOL_ROLE_ADMIN => 'admin',
       BX_DOL_ROLE_MEMBER => 'member'
    );


    $sID = isset($_COOKIE['memberID']) ? bx_process_input($_COOKIE['memberID']) : false;
    $sPassword = isset($_COOKIE['memberPassword']) ? bx_process_input($_COOKIE['memberPassword']) : false;

    $bLogged = false;
    foreach ($aAccTypes as $iRole => $sValue) {
        if ($GLOBALS['logged'][$sValue] = ($sID && !bx_check_login($sID, $sPassword, $iRole))) {
            $bLogged = true;
            break;
        }
    }
    
    if ((isset($_COOKIE['memberID']) || isset($_COOKIE['memberPassword'])) && !$bLogged)
        bx_logout(false);
}


/**
 * check unencrypted password
 * @return empty string on success or error string on error
 */
function bx_check_password($sLogin, $sPassword, $iRole = BX_DOL_ROLE_MEMBER) {

    bx_import('BxDolAccount');
    $oAccount = BxDolAccount::getInstance($sLogin);        
    if (!$oAccount) {
        bx_import('BxDolLanguages');
        return _t("_sys_txt_login_error");
    }

    $aAccountInfo = $oAccount->getInfo();        

    $sPassCheck = encryptUserPwd($sPassword, $aAccountInfo['salt']);

    if ($sErrorMsg = bx_check_login($aAccountInfo['id'], $sPassCheck, $iRole))
        return $sErrorMsg;

    // Admin can always login even if he is blocked/banned/suspended/etc
    if (isAdmin($aAccountInfo['id']))
        return '';

    $sErrorMsg = '';
    bx_alert('account', 'check_login',  $aAccountInfo['id'], false, array('error_msg' => &$sErrorMsg));
    return $sErrorMsg;
}


/**
 * check encrypted password (ex., from Cookie)
 * @return empty string on success or error string on error
 */
function bx_check_login($iID, $sPassword, $iRole = BX_DOL_ROLE_MEMBER) {

    bx_import('BxDolAccount');
    $oAccount = BxDolAccount::getInstance((int)$iID);

    // If no such account available
    if (!$oAccount) { 
        bx_import('BxDolLanguages');       
        return _t("_sys_txt_login_error");
    }

    $aAccountInfo = $oAccount->getInfo();

    // If password is incorrect
    if (strcmp($aAccountInfo['password'], $sPassword) != 0) {
        bx_import('BxDolLanguages');
        return _t("_sys_txt_login_error");
    }

    // If wrong account role
    if (!((int)$aAccountInfo['role'] & $iRole)) {
        bx_import('BxDolLanguages');
        return _t("_sys_txt_login_invalid_role");
    }

    return '';
}

function bx_require_authentication ($bStudio = false) {

    $iRole = BX_DOL_ROLE_MEMBER;
    if ($bStudio)
        $iRole = BX_DOL_ROLE_ADMIN;

    $sID = isset($_COOKIE['memberID']) ? bx_process_input($_COOKIE['memberID']) : false;
    $sPassword = isset($_COOKIE['memberPassword']) ? bx_process_input($_COOKIE['memberPassword']) : false;
    
    if ($sLoginError = bx_check_login($sID, $sPassword, $iRole)) {
        bx_login_form($bStudio);
    }   

    check_logged();
}



function bx_login_form($bStudio = false, $bAjaxMode = false) {

    if ($bStudio == 1) {
        bx_import("BxTemplStudioFunctions");
        BxTemplStudioFunctions::getInstance()->getLoginForm();
        exit;
    }    

    $sFormCode = BxDolService::call('system', 'login_form', array(), 'TemplServiceLogin');

    if ($bAjaxMode) {
        echo $GLOBALS['oFunctions']->transBox('', $sFormCode, false, true);
        exit;
    }

    BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_DEFAULT);
    BxDolTemplate::getInstance()->setPageHeader (getParam('site_title') . ' ' . _t("_Member Login"));
    BxDolTemplate::getInstance()->setPageContent ('page_main_code', DesignBoxContent(_t("_Member Login"), $sFormCode, BX_DB_PADDING_DEF));
    BxDolTemplate::getInstance()->getPageCode();

    exit;
}


/**
 * get corrently logged in profile id
 */
function bx_get_logged_profile_id () {
    bx_import('BxDolProfile');
    $o = BxDolProfile::getInstance();
    return $o ? $o->id() : false;
}

check_logged();

