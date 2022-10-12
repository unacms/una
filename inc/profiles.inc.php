<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @return corrently logged in profile id
 */
function bx_get_logged_profile_id ()
{
    $o = BxDolProfile::getInstance();
    return $o ? $o->id() : false;
}

/**
 * @return true if user is logged in
 */
function isLogged()
{
    return getLoggedId() != 0;
}

/**
 * @return logged in account id
 */
function getLoggedId()
{
    return isset($_COOKIE['memberID']) && (!empty($GLOBALS['logged']['member']) || !empty($GLOBALS['logged']['admin'])) ? (int)$_COOKIE['memberID'] : 0;
}

/**
 * @return logged in account password
 */
function getLoggedPassword()
{
    return isset($_COOKIE['memberPassword']) && ($GLOBALS['logged']['member'] || $GLOBALS['logged']['admin']) ? $_COOKIE['memberPassword'] : '';
}

/**
 * It checks if account role is member.
 */
function isMember($iId = 0)
{
    return isRole(BX_DOL_ROLE_MEMBER, $iId);
}

if (!function_exists("isAdmin")) {
    /**
     * @return true if account is admin
     */
    function isAdmin($iId = 0)
    {
        if (!$iId && isset($GLOBALS['logged']['admin']) && $GLOBALS['logged']['admin']) // easier check for currently logged in user
            return true;
        return isRole(BX_DOL_ROLE_ADMIN, $iId);
    }
}

/**
 * Check user role
 * @param $iRole role to check user for
 * @param $iId optional account id, if it isn't specified then curently logged in account is used
 * @return true if user is in the provided role
 */
function isRole($iRole, $iId = 0)
{
    if (!(int)$iId)
        $iId = getLoggedId();

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

/**
 * Login user by setting necessary cookies
 * @param $iId account id
 * @param $bRememberMe remember session
 * @return false if id isn't correct or array of user info if user was logged in
 */
function bx_login($iId, $bRememberMe = false)
{
    $oAccountQuery = BxDolAccountQuery::getInstance();

    $sPassword = $oAccountQuery->getPassword($iId);
    if (!$sPassword)
        return false;

    $iCookieTime = $bRememberMe ? time() + 60 * getParam('sys_session_lifetime_in_min') : 0;
    bx_setcookie("memberID", $iId, $iCookieTime, 'auto');
    $_COOKIE['memberID'] = $iId;
    bx_setcookie("memberPassword", $sPassword, $iCookieTime, 'auto', '', 'auto', true /* http only */);
    $_COOKIE['memberPassword'] = $sPassword;

    BxDolSession::getInstance()->setUserId($iId);

    $oAccountQuery->updateLoggedIn($iId);

    bx_alert('account', 'login',  $iId);
    
    bx_audit(
        $iId, 
        'bx_accounts', 
        '_sys_audit_action_account_login',  
        array('content_title' => '', 'data' => ['display_info' => ['User agent' => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '')]])
    );
    
    return $oAccountQuery->getInfoById($iId);
}

/**
 * Logout user by removing cookies
 */
function bx_logout($bNotify = true)
{
    if ($bNotify && isMember())
        bx_alert('account', 'logout', (int)$_COOKIE['memberID']);

    bx_audit(
        $_COOKIE['memberID'], 
        'bx_accounts', 
        '_sys_audit_action_account_logout',  
        array('content_title' => '', 'data' => ['display_info' => ['User agent' => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '')]])
    );

    bx_setcookie('memberID', '', time() - 96 * 3600);
    bx_setcookie('memberPassword', '', time() - 96 * 3600, 'auto', '', 'auto', true /* http only */);

    unset($_COOKIE['memberID']);
    unset($_COOKIE['memberPassword']);

    BxDolSession::getInstance()->destroy();
}

/**
 * Check if user is logged in (necessary cookies are present) and set some global variables
 */
function check_logged()
{
    $aAccTypes = array(
       BX_DOL_ROLE_ADMIN => 'admin',
       BX_DOL_ROLE_MEMBER => 'member'
    );

    $bID = isset($_COOKIE['memberID']);
    $sID = $bID ? bx_process_input($_COOKIE['memberID']) : false;

    $bPassword = isset($_COOKIE['memberPassword']);
    $sPassword = $bPassword ? bx_process_input($_COOKIE['memberPassword']) : false;

    $bLogged = false;
    foreach ($aAccTypes as $iRole => $sValue) {
        if ($GLOBALS['logged'][$sValue] = ($sID && !bx_check_login($sID, $sPassword, $iRole))) {
            BxDolSession::getInstance();
            $bLogged = true;
            break;
        }
    }

    if($bID && $bPassword && $bLogged) {
        header("Cache-Control: no-cache, no-store, must-revalidate");
        bx_alert('account', 'logged', getLoggedId());
    }

    if(($bID || $bPassword) && !$bLogged)
        bx_logout(false);
}

/**
 * Check unencrypted password
 * @return empty string on success or error string on error
 */
function bx_check_password($sLogin, $sPassword, $iRole = BX_DOL_ROLE_MEMBER)
{
    $oAccount = BxDolAccount::getInstance($sLogin);
    if (!$oAccount) {
        bx_import('BxDolLanguages');
        return _t("_sys_txt_login_error");
    }
    
    if ($oAccount->isLocked()){
        bx_import('BxDolLanguages');
		return _t("_sys_txt_login_locked", bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password')));
	}

    $aAccountInfo = $oAccount->getInfo();

    $sPassCheck = encryptUserPwd($sPassword, $aAccountInfo['salt']);

	// regenerate password using another encrypt function if necessary
	bx_alert('system', 'encrypt_password_after', 0, false, array(
            'info' => $aAccountInfo,
			'pwd' => $sPassword,
            'password' => &$sPassCheck,
        ));
    
    if ($sErrorMsg = bx_check_login($aAccountInfo['id'], $sPassCheck, $iRole)){
        bx_audit(
            $aAccountInfo['id'], 
            'bx_accounts', 
            '_sys_audit_action_account_failed_login_attempt',  
            array('content_title' => '', 'data' => ['display_info' => ['User agent' => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '')]]),
            $aAccountInfo['profile_id']
        );

        $iMaxLoginAttempts = getParam('sys_account_limit_incorrect_login_attempts');
        if ($iMaxLoginAttempts >0){
            $oAccountQuery = BxDolAccountQuery::getInstance();
            if ($aAccountInfo['login_attempts'] >= $iMaxLoginAttempts){
                $oAccountQuery->lockAccount($aAccountInfo['id'], 1);
                bx_import('BxDolLanguages');
                return _t("_sys_txt_login_locked", bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password')));
            }
            else{
                $oAccountQuery->updateAttemptsCounter($aAccountInfo['id']);
            }
        }
        return $sErrorMsg;
    }

    // Admin can always login even if he is blocked/banned/suspended/etc
    if (isAdmin($aAccountInfo['id']))
        return '';

    $sErrorMsg = '';
    bx_alert('account', 'check_login',  $aAccountInfo['id'], false, array('error_msg' => &$sErrorMsg));
    
    return $sErrorMsg;
}

/**
 * Check encrypted password (ex., from Cookie)
 * @return empty string on success or error string on error
 */
function bx_check_login($iID, $sPassword, $iRole = BX_DOL_ROLE_MEMBER)
{
    $oAccount = BxDolAccount::getInstance((int)$iID);

    // If no such account available
    if (!$oAccount) {
        bx_import('BxDolLanguages');
        return _t("_sys_txt_login_error");
    }

    $aAccountInfo = $oAccount->getInfo();

    // If password is incorrect
    if (strcmp($aAccountInfo['password'], $sPassword) !== 0) {
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

/**
 * Declare that content is require user authoriztion and display login form if user isn't logged in
 * @param $bStudio require webmaster authorization
 */
function bx_require_authentication ($bStudio = false, $bAjaxMode = false, $sForceRelocate = '')
{
    $iRole = BX_DOL_ROLE_MEMBER;
    if ($bStudio)
        $iRole = BX_DOL_ROLE_ADMIN;

    $sID = isset($_COOKIE['memberID']) ? bx_process_input($_COOKIE['memberID']) : false;
    $sPassword = isset($_COOKIE['memberPassword']) ? bx_process_input($_COOKIE['memberPassword']) : false;

    if (bx_check_login($sID, $sPassword, $iRole)) {
        bx_login_form($bStudio, $bAjaxMode, $sForceRelocate);
    }

    check_logged();
}



/**
 * Display login form and exit
 * @param $bStudio display login form for studio
 * @param $bAjaxMode login form displayed via AJAX
 * @param sForceRelocate forece relocate
 */
function bx_login_form($bStudio = false, $bAjaxMode = false, $sForceRelocate = '')
{
    if ($bStudio == 1) {
        BxTemplStudioFunctions::getInstance()->getLoginForm();
        exit;
    }

    $sFormCode = BxDolService::call('system', 'login_form', array('', $sForceRelocate), 'TemplServiceLogin');

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

check_logged();

/** @} */
