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


/**
 * function for inner using only
 * @param $ID - profile ID
 * @param $iFrStatus - friend status (1 - approved, 0 - wait)
 * @param $iOnline - filter for last nav moment (in minutes)
 * @param $sqlWhere - add sql Conditions, should beginning from AND
 */
function getFriendNumber($iID, $iFrStatus = 1, $iOnline = 0, $sqlWhere = '') {
    $sqlAdd = '';

    if ($iOnline > 0)
        $sqlAdd = " AND (p.`DateLastNav` > SUBDATE(NOW(), INTERVAL " . $iOnline . " MINUTE))";

    if (strlen($sqlWhere) > 0)
        $sqlAdd .= $sqlWhere;

    $sqlQuery = "SELECT COUNT(`f`.`ID`)
    FROM
    (SELECT `ID` AS `ID` FROM `sys_friend_list` WHERE `Profile` = '{$iID}' AND `Check` = {$iFrStatus}
    UNION
    SELECT `Profile` AS `ID` FROM `sys_friend_list` WHERE `ID` = '{$iID}' AND `Check` = {$iFrStatus})
    AS `f`
    INNER JOIN `Profiles` AS `p` ON `p`.`ID` = `f`.`ID`
    WHERE 1 {$sqlAdd}";

    return (int)db_value($sqlQuery);
}

/**
 * Get number of friend requests sent to the specified profile.
 * It doesn't count pending friend requests which was sent by specified profile.
 * @param $iID specified profile
 * @return number of friend requests
 */
function getFriendRequests($iID) {
    $iID = (int)$iID;
    $sqlQuery = "SELECT count(*) FROM `sys_friend_list` WHERE `Profile` = {$iID} AND `Check` = '0'";
    return (int)db_value($sqlQuery);
}

function getMyFriendsEx($iID, $sWhereParam = '', $sSortParam = '', $sqlLimit = '') {
    $sJoin = $sOrderBy = '';

    switch($sSortParam) {

        case 'activity' :
        case 'last_nav' : // DateLastNav
            $sOrderBy = 'ORDER BY p.`DateLastNav`';
            break;
        case 'activity_desc' :
        case 'last_nav_desc' : // DateLastNav
            $sOrderBy = 'ORDER BY p.`DateLastNav` DESC';
            break;
        case 'date_reg' : // DateReg
            $sOrderBy = 'ORDER BY p.`DateReg`';
            break;
        case 'date_reg_desc' : // DateReg
            $sOrderBy = 'ORDER BY p.`DateReg` DESC';
            break;
        case 'image' : // Avatar
            $sOrderBy = 'ORDER BY p.`Avatar` DESC';
            break;
        case 'rate' : // `sys_profile_rating`.`pr_rating_sum
            $sOrderBy = 'ORDER BY `sys_profile_rating`.`pr_rating_sum`';
            $sJoin = 'LEFT JOIN `sys_profile_rating` ON p.`ID` = `sys_profile_rating`.`pr_id`';
            break;
        default : // DateLastNav
            $sOrderBy = 'ORDER BY p.`DateLastNav` DESC';
            break;
    }

    $sLimit = ($sqlLimit == '') ? '' : /*"LIMIT 0, " .*/ $sqlLimit;
    $iOnlineTime = (int)getParam( "member_online_time" );
    $sqlQuery = "SELECT `p`.*, `f`.`ID`,
                if(`DateLastNav` > SUBDATE(NOW( ), INTERVAL $iOnlineTime MINUTE ), 1, 0) AS `is_online`,
                UNIX_TIMESTAMP(p.`DateLastLogin`) AS 'TS_DateLastLogin', UNIX_TIMESTAMP(p.`DateReg`) AS 'TS_DateReg'     FROM (
                SELECT `ID` AS `ID` FROM `sys_friend_list` WHERE `Profile` = '{$iID}' AND `Check` =1
                UNION
                SELECT `Profile` AS `ID` FROM `sys_friend_list` WHERE `ID` = '{$iID}' AND `Check` =1
            ) AS `f`
            INNER JOIN `Profiles` AS `p` ON `p`.`ID` = `f`.`ID`
            {$sJoin}
            WHERE 1 {$sWhereParam}
            {$sOrderBy}
            {$sLimit}";

    $aFriends = array();

    $vProfiles = db_res($sqlQuery);
    while ($aProfiles = mysql_fetch_assoc($vProfiles)) {
        $aFriends[$aProfiles['ID']] = array($aProfiles['ID'], $aProfiles['TS_DateLastLogin'], $aProfiles['TS_DateReg'], $aProfiles['Rate'], $aProfiles['DateLastNav'], $aProfiles['is_online']);
    }

    return $aFriends;
}

function isLoggedBanned($iCurUserID = 0) {
    $iCCurUserID = ($iCurUserID>0) ? $iCurUserID : (int)$_COOKIE['memberID'];
    if ($iCCurUserID) {
        $CheckSQL = "
            SELECT *
            FROM `sys_admin_ban_list`
            WHERE `ProfID`='{$iCCurUserID}'
        ";
        db_res($CheckSQL);
        if (db_affected_rows()>0) {
            return true;
        }
    }
    return false;
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

    $oAccountQuery->updateLoggedIn($iId);

    bx_alert('account', 'login',  $iId);

    return $oAccountQuery->getInfoById($iId);
}

function bx_logout($bNotify = true) {
    if ($bNotify && isMember())
        bx_alert('profile', 'logout', (int)$_COOKIE['memberID']);

    $aUrl = parse_url(BX_DOL_URL_ROOT);
    $sPath = isset($aUrl['path']) && !empty($aUrl['path']) ? $aUrl['path'] : '/';

    setcookie('memberID', '', time() - 96 * 3600, $sPath);
    setcookie('memberPassword', '', time() - 96 * 3600, $sPath);

    unset($_COOKIE['memberID']);
    unset($_COOKIE['memberPassword']);
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

    return bx_check_login($aAccountInfo['id'], $sPassCheck, $iRole);
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

    // Admin can always login even if his ip is blocked
    if (isAdmin($aAccountInfo['id']))
        return '';

    // If IP is banned
    if ((2 == getParam('ipBlacklistMode') && bx_is_ip_blocked()) || ('on' == getParam('sys_dnsbl_enable') && bx_is_ip_dns_blacklisted('', 'login'))) {
        bx_import('BxDolLanguages');
        return _t('_Sorry, your IP been banned');
    }

    // If account is banned
    if (isLoggedBanned($aAccountInfo['id'])) {
        bx_import('BxDolLanguages');
        return _t('_member_banned');
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
        echo $GLOBALS['oFunctions']->transBox($sFormCode, true);
        exit;
    }

    BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_DEFAULT);
    BxDolTemplate::getInstance()->setPageHeader (getParam('site_title') . ' ' . _t("_Member Login"));
    BxDolTemplate::getInstance()->setPageContent ('page_main_code', DesignBoxContent(_t("_Member Login"), $sFormCode, BX_DB_PADDING_DEF));
    BxDolTemplate::getInstance()->getPageCode();

    exit;
}

/**
 * Check profile existing, membership/acl, profile status and privacy.
 * If some of visibility options are not allowed then appropritate page is shown and exit called.
 * @param $iViewedId viewed member id
 * @param $iViewerId viewer member id
 * @return nothing
 */
function bx_check_profile_visibility ($iViewedId, $iViewerId = 0) {

    global $logged, $_page, $_page_cont, $p_arr;

    $oTemplate = BxDolTemplate::getInstance();

    // check if profile exists
    if (!$iViewedId) {
        $oTemplate->displayPageNotFound ();
	    exit;
    }

    // check if viewer can view profile
    $check_res = checkAction( $iViewerId, ACTION_ID_VIEW_PROFILES, true, $iViewedId );
    if ($check_res[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED
        && !$logged['admin'] && !$logged['moderator'] && $iViewerId != $iViewedId)
    {
        $oTemplate->setPageNameIndex(0);
        $oTemplate->setPageHeader(getParam('site_title') . " " . _t("_Member Profile"));        
        $oTemplate->setPageContent('page_main_code', MsgBox($check_res[CHECK_ACTION_MESSAGE]));
        $oTemplate->getPageCode();
        exit;
    }

    $oProfile = new BxBaseProfileGenerator( $iViewedId );
    $p_arr  = $oProfile -> _aProfile;

    // check if viewed member is active
    if (!($p_arr['ID'] && ($logged['admin'] || $logged['moderator'] || $oProfile->owner || $p_arr['Status'] == 'Active')))
    {
        header("HTTP/1.1 404 Not Found");
        $oTemplate->displayMsg(_t("_Profile NA"));
        exit;
    }

    // check privacy
    if (!$logged['admin'] && !$logged['moderator'] && $iViewerId != $iViewedId) {
        $oPrivacy = new BxDolPrivacy('Profiles', 'ID', 'ID');
        if (!$oPrivacy->check('view', $iViewedId, $iViewerId)) {
            bx_import('BxDolProfilePrivatePageView');
            $oProfilePrivateView = new BxDolProfilePrivatePageView($oProfile);
            $oTemplate->setPageNameIndex(7);
            $oTemplate->setPageContent('page_main_code', $oProfilePrivateView->getCode());
            $oTemplate->getPageCode();
            exit;
        }
    }
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

