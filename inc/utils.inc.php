<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

define('BX_DOL_LOCALE_TIME', 2);
define('BX_DOL_LOCALE_DATE_SHORT', 4);
define('BX_DOL_LOCALE_DATE', 5);

define('BX_DOL_LOCALE_PHP', 1);
define('BX_DOL_LOCALE_DB', 2);

define('BX_DATA_TEXT', 1); ///< regular text data type
define('BX_DATA_TEXT_MULTILINE', 2); ///< regular multiline text data type
define('BX_DATA_INT', 3); ///< integer data type
define('BX_DATA_FLOAT', 4); ///< float data type
define('BX_DATA_CHECKBOX', 5); ///< checkbox data type, 'on' or empty value
define('BX_DATA_HTML', 6); ///< HTML data type
define('BX_DATA_DATE', 7); ///< date data type stored as yyyy-mm-dd
define('BX_DATA_DATE_TS', 8); ///< date data type stored as unixtimestamp
define('BX_DATA_DATETIME_TS', 9); ///< date/time data type stored as unixtimestamp

define('BX_ESCAPE_STR_AUTO', 0); ///< turn apostropes and quote signs into html special chars, for use in @see bx_js_string and @see bx_html_attribute
define('BX_ESCAPE_STR_APOS', 1); ///< escape apostrophes only, for js strings enclosed in apostrophes, for use in @see bx_js_string and @see bx_html_attribute
define('BX_ESCAPE_STR_QUOTE', 2); ///< escape quotes only, for js strings enclosed in quotes, for use in @see bx_js_string and @see bx_html_attribute

define('BX_EMAIL_SYSTEM', 0); ///< system email without unsubscribe link, like forgot password or email verification
define('BX_EMAIL_NOTIFY', 1); ///< notification message, with unsubscribe link
define('BX_EMAIL_MASS', 2); ///< mass email, one mesage send to manu users, with unsubscribe link

/**
 * The following two functions are needed to convert title to uri and back.
 * It usefull when titles are used in URLs, like in Categories and Tags.
 */
function title2uri($sValue) {
    return str_replace(
        array('&', '/', '\\', '"', '+'),
        array('[and]', '[slash]', '[backslash]', '[quote]', '[plus]'),
        $sValue
    );
}
function uri2title($sValue) {
    return str_replace(
        array('[and]', '[slash]', '[backslash]', '[quote]', '[plus]'),
        array('&', '/', '\\', '"', '+'),
        $sValue
    );
}

/**
 * Convert date(timestamp) in accordance with requested format code.
 *
 * @param string $sTimestamp - timestamp
 * @param integer $iCode - format code
 *                  1(4) - short date format. @see sys_options -> short_date_format_php
 *                  2 - time format. @see sys_options -> time_format_php
 *                  3(5) - long date format. @see sys_options -> date_format_php
 *                  6 - RFC 2822 date format.
 */
function getLocaleDate($sTimestamp = '', $iCode = BX_DOL_LOCALE_DATE_SHORT) {
    $sFormat = (int)$iCode == 6 ? 'r' : getLocaleFormat($iCode);

    return date($sFormat, $sTimestamp);
}
/**
 * Get data format in accordance with requested format code and format type.
 *
 * @param integer $iCode - format code
 *                  1(4) - short date format. @see sys_options -> short_date_format_php
 *                  2 - time format. @see sys_options -> time_format_php
 *                  3(5) - long date format. @see sys_options -> date_format_php
 *                  6 - RFC 2822 date format.
 * @param integer $iType - format type
 *                  1 - for PHP code.
 *                  2 - for database.
 */
function getLocaleFormat($iCode = BX_DOL_LOCALE_DATE_SHORT, $iType = BX_DOL_LOCALE_PHP) {
    $sPostfix = (int)$iType == BX_DOL_LOCALE_PHP ? '_php' : '';

    $sResult = '';
    switch ($iCode) {
        case 2:
            $sResult = getParam('time_format' . $sPostfix);
            break;
        case 1:
        case 4:
            $sResult = getParam('short_date_format' . $sPostfix);
            break;
        case 3:
        case 5:
            $sResult = getParam('date_format' . $sPostfix);
            break;
    }

    return $sResult;
}

/**
 * Function will check on blocked status;
 *
 * @param  : $iFirstProfile (integer) - first profile's id;
 * @param  : $iSecondProfile (integer) - second profile's id;
 * @return : (boolean) - true if pair will blocked;
 */
function isBlocked($iFirstProfile, $iSecondProfile)
{
    $iFirstProfile = (int)$iFirstProfile;
    $iSecondProfile = (int)$iSecondProfile;
    $sQuery = "SELECT COUNT(*) FROM `sys_block_list` WHERE `ID` = {$iFirstProfile} AND `Profile` = {$iSecondProfile}";
    return db_value( $sQuery) ? true : false;
}

/*
 * function for work with profile
 */
function is_friends($id1, $id2) {
    $id1 = (int)$id1;
    $id2 = (int)$id2;
    if ($id1 == 0 || $id2 == 0)
       return;
    $cnt = db_arr("SELECT SUM(`Check`) AS 'cnt' FROM `sys_friend_list` WHERE `ID`='{$id1}' AND `Profile`='{$id2}' OR `ID`='{$id2}' AND `Profile`='{$id1}'");
    return ($cnt['cnt'] > 0 ? true : false);
}

/*
 * functions for limiting maximal word length
 */
function strmaxwordlen($input, $len = 100) {
    return $input;
}

/*
 * functions for limiting maximal text length
 */
function strmaxtextlen($input, $len = 60) {
    if ( strlen($input) > $len )
        return mb_substr($input, 0, $len - 4) . "...";
    else
        return $input;
}

function html2txt($content, $tags = "") {
    while($content != strip_tags($content, $tags)) {
        $content = strip_tags($content, $tags);
    }

    return $content;
}

function html_encode($text) {
     $searcharray =  array(
    "'([-_\w\d.]+@[-_\w\d.]+)'",
    "'((?:(?!://).{3}|^.{0,2}))(www\.[-\d\w\.\/]+)'",
    "'(http[s]?:\/\/[-_~\w\d\.\/]+)'");

    $replacearray = array(
    "<a href=\"mailto:\\1\">\\1</a>",
    "\\1http://\\2",
    "<a href=\"\\1\" target=_blank>\\1</a>");

   return preg_replace($searcharray, $replacearray, stripslashes($text));
}

/**
 * Functions to process user input.
 * DON'T use to process data before passing to SQL query - use db prepare instead @see BxDolDb::prepare.
 * It is ok to use bx_process_input and then db prepare.
 * @param $mixedData data to process
 * @param $iDataType how to handle data, possible valies:
 *          BX_DATA_INT - integer value
 *          BX_DATA_FLOAT - float values
 *          BX_DATA_CHECKBOX - 'on' or empty string
 *          BX_DATA_TEXT - text data, single line (default)
 *          BX_DATA_TEXT_MULTILINE - text data, multiple lines
 *          BX_DATA_HTML - HTML data
 *          BX_DATA_DATE - date data type stored as yyyy-mm-dd
 *          BX_DATA_DATE_TS' -  date data type stored as unixtimestamp
 *          BX_DATA_DATETIME_TS - date/time data type stored as unixtimestamp
 * @param $mixedParams optional parameters to pass for validation
 * @return the filtered data, or FALSE if the filter fails.
 */
function bx_process_input ($mixedData, $iDataType = BX_DATA_TEXT, $mixedParams = false, $isCheckMagicQuotes = true) {

    if (is_array($mixedData)) {
        foreach ($mixedData as $k => $v)
            $mixedData[$k] = bx_process_input($v, $iDataType, $mixedParams);
        return $mixedData;
    }

    if (get_magic_quotes_gpc() && $isCheckMagicQuotes)
        $mixedData = stripslashes($mixedData);

    switch ($iDataType) {
    case BX_DATA_INT:
        return filter_var(trim($mixedData), FILTER_VALIDATE_INT);
    case BX_DATA_FLOAT:
        return filter_var(trim($mixedData), FILTER_VALIDATE_FLOAT);
    case BX_DATA_CHECKBOX:
        return 'on' == trim($mixedData) ? 'on' : '';

    case BX_DATA_DATE:
        // maybe consider using strtotime
        $mixedData = trim($mixedData);
        if (!preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $mixedData))
            return false;
        list($iYear, $iMonth, $iDay) = explode('-', $mixedData); // 1985-10-28
        $iDay   = intval($iDay);
        $iMonth = intval($iMonth);
        $iYear  = intval($iYear);
        return sprintf("%04d-%02d-%02d", $iYear, $iMonth, $iDay);
    case BX_DATA_DATE_TS:
        $mixedData = trim($mixedData);
        if (!preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $mixedData))
            return false;
        list($iYear, $iMonth, $iDay) = explode('-', $mixedData);
        $iDay   = intval($iDay);
        $iMonth = intval($iMonth);
        $iYear  = intval($iYear);
        $iRet = mktime (0, 0, 0, $iMonth, $iDay, $iYear);
        return $iRet > 0 ? $iRet : false;
    case BX_DATA_DATETIME_TS:
        if (!preg_match('#(\d+)\-(\d+)\-(\d+)[\sT]{1}(\d+):(\d+):(\d+)#', $mixedData, $m))
            return bx_process_input ($mixedData, BX_DATA_DATE_TS, $mixedParams, $isCheckMagicQuotes);
        $iDay   = $m[3];
        $iMonth = $m[2];
        $iYear  = $m[1];
        $iH = $m[4];
        $iM = $m[5];
        $iS = $m[6];
        $iRet = mktime ($iH, $iM, $iS, $iMonth, $iDay, $iYear);
        return $iRet > 0 ? $iRet : false;

    case BX_DATA_HTML:
        return clear_xss($mixedData);
    case BX_DATA_TEXT_MULTILINE:
        return nl2br(strip_tags($mixedData));
    case BX_DATA_TEXT:
    default:
        return $mixedData;
    }
}

/*
 * Functions to process user output.
 * Always use this function before output data which was entered by user before.
 * @param $mixedData string to process
 * @param $iDataType how to handle data, possible valies the same as in bx_process_input function, see bx_process_input.
 * @param $mixedParams optional parameters to pass for validation
 * @return the filtered data, or FALSE if the filter fails.
 */
function bx_process_output ($mixedData, $iDataType = BX_DATA_TEXT, $mixedParams = false) {

    if (is_array($mixedData)) {
        foreach ($mixedData as $k => $v)
            $mixedData[$k] = bx_process_output($v, $iDataType, $mixedParams);
        return $mixedData;
    }

    switch ($iDataType) {
    case BX_DATA_INT:
        return filter_var($mixedData, FILTER_VALIDATE_INT);
    case BX_DATA_FLOAT:
        return filter_var($mixedData, FILTER_VALIDATE_FLOAT);
    case BX_DATA_CHECKBOX:
        return 'on' == trim($mixedData) ? 'on' : '';

    case BX_DATA_DATE:
        return $mixedData;
    case BX_DATA_DATE_TS:
        return empty($mixedData) ? '' : date("Y-m-d", $mixedData);
    case BX_DATA_DATETIME_TS:
        return empty($mixedData) ? '' : date("Y-m-d H:i:s", $mixedData);

    case BX_DATA_HTML:
        return $mixedData;
    case BX_DATA_TEXT_MULTILINE:
        return $mixedData;
    case BX_DATA_TEXT:
    default:
        return htmlspecialchars_adv($mixedData);
    }
}

/*
 * This function apply bx_process_input and then bx_process_output.
 * Use this function to output data immediately after receiving, without saving to database.
 * Patams are the same as bx_process_input function - @see bx_process_input
 */
function bx_process_pass ($mixedData, $iDataType = BX_DATA_TEXT, $mixedParams = false, $isCheckMagicQuotes = true) {
    return bx_process_output(bx_process_input ($mixedData, $iDataType, $mixedParams, $isCheckMagicQuotes), $iDataType, $mixedParams);
}

/**
 * DEPRECATED
 * use bx_process_input + bx_process_output instead
 * --------
 * function for processing pass data
 *
 * This function cleans the GET/POST/COOKIE data if magic_quotes_gpc() is on
 * for data which should be outputed immediately after submit
 */
/*
function process_pass_data( $text, $strip_tags = 0 ) {
    if ( $strip_tags )
        $text = strip_tags($text);

    if ( !get_magic_quotes_gpc() )
        return $text;
    else
        return stripslashes($text);
}
*/

/*
 * function for output data from database into html
 */
function htmlspecialchars_adv( $string ) {
    return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
}

function process_text_output( $text, $maxwordlen = 100 ) {
    return ( htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) ) );
}

function process_text_withlinks_output( $text, $maxwordlen = 100 ) {
    return nl2br( html_encode( htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) ) ) );
}

function process_line_output( $text, $maxwordlen = 100 ) {
    return htmlspecialchars_adv( strmaxwordlen( $text, $maxwordlen ) );
}

function process_html_output( $text, $maxwordlen = 100 ) {
    return strmaxwordlen( $text, $maxwordlen );
}

/**
*    Used to construct sturctured arrays in GET or POST data. Supports multidimensional arrays.
*
*    @param array    $Values    Specifies values and values names, that should be submitted. Can be multidimensional.
*
*    @return string    HTML code, which contains <input type="hidden"...> tags with names and values, specified in $Values array.
*/
function ConstructHiddenValues($Values) {
    /**
    *    Recursive function, processes multidimensional arrays
    *
    *    @param string $Name    Full name of array, including all subarrays' names
    *
    *    @param array $Value    Array of values, can be multidimensional
    *
    *    @return string    Properly consctructed <input type="hidden"...> tags
    */
    function ConstructHiddenSubValues($Name, $Value)     {
        if (is_array($Value)) {
            $Result = "";
            foreach ($Value as $KeyName => $SubValue) {
                $Result .= ConstructHiddenSubValues("{$Name}[{$KeyName}]", $SubValue);
            }
        } else
            // Exit recurse
            $Result = "<input type=\"hidden\" name=\"".htmlspecialchars($Name)."\" value=\"".htmlspecialchars($Value)."\" />\n";

        return $Result;
    }
    /* End of ConstructHiddenSubValues function */

    $Result = '';
    if (is_array($Values)) {
        foreach ($Values as $KeyName => $Value) {
            $Result .= ConstructHiddenSubValues($KeyName, $Value);
        }
    }

    return $Result;
}

/**
*    Returns HTML/javascript code, which redirects to another URL with passing specified data (through specified method)
*
*    @param string    $ActionURL    destination URL
*
*    @param array    $Params    Parameters to be passed (through GET or POST)
*
*    @param string    $Method    Submit mode. Only two values are valid: 'get' and 'post'
*
*    @return mixed    Correspondent HTML/javascript code or false, if input data is wrong
*/
function RedirectCode($ActionURL, $Params = NULL, $Method = "get", $Title = 'Redirect') {
    if ((strcasecmp(trim($Method), "get") && strcasecmp(trim($Method), "post")) || (trim($ActionURL) == ""))
        return false;

    ob_start();

?>
<html>
    <head>
        <title><?= $Title ?></title>
    </head>
    <body>
        <form name="RedirectForm" action="<?= htmlspecialchars($ActionURL) ?>" method="<?= $Method ?>">

<?= ConstructHiddenValues($Params) ?>

        </form>
        <script type="text/javascript">
            <!--
            document.forms['RedirectForm'].submit();
            -->
        </script>
    </body>
</html>
<?

    $Result = ob_get_contents();
    ob_end_clean();

    return $Result;
}

/**
*    Redirects browser to another URL, passing parameters through POST or GET
*    Actually just prints code, returned by RedirectCode (see RedirectCode)
*/
function Redirect($ActionURL, $Params = NULL, $Method = "get", $Title = 'Redirect') {
    $RedirectCodeValue = RedirectCode($ActionURL, $Params, $Method, $Title);
    if ($RedirectCodeValue !== false)
        echo $RedirectCodeValue;
}

function isRWAccessible($sFileName) {
    clearstatcache();
    $perms = fileperms($sFileName);
    return ( $perms & 0x0004 && $perms & 0x0002 ) ? true : false;
}

/**
 * Send email function
 *
 * @param $sRecipientEmail - Email where email should be send
 * @param $sMailSubject - subject of the message
 * @param $sMailBody - Body of the message
 * @param $iRecipientID - ID of recipient profile
 * @param $aPlus - Array of additional information
 * @param $iEmailType - email message type: BX_EMAIL_SYSTEM, BX_EMAIL_NOTIFY or BX_EMAIL_MASS
 * @return true if message was send or false otherwise
 */
function sendMail($sRecipientEmail, $sMailSubject, $sMailBody, $iRecipientID = 0, $aPlus = array(), $iEmailType = BX_EMAIL_NOTIFY, $sEmailFlag = 'html', $isDisableAlert = false) {

    // make sure that recipient's email is valid and message isn't empty
    if (!$sMailBody || !$sRecipientEmail || preg_match('/\(2\)$/', $sRecipientEmail))
        return false;

    // get recipient account
    bx_import('BxDolAccount');
    $oAccount = BxDolAccount::getInstance($sRecipientEmail);
    $aAccountInfo = $oAccount ? $oAccount->getInfo() : false;

    // don't send bulk emails if user didn't subscribed to site news or email is unconfirmed
    if ($aAccountInfo && BX_EMAIL_MASS == $iEmailType && (!$aAccountInfo['email_confirmed'] || !$aAccountInfo['receive_news']))
        return false;

    // don't send email notifications if user didn't subscribed to notifications or email is unconfirmed
    if ($aAccountInfo && BX_EMAIL_NOTIFY == $iEmailType && (!$aAccountInfo['email_confirmed'] || !$aAccountInfo['receive_updates']))
        return false;

    // if profile id is provided - get profile's info
    $aRecipientInfo = false;
    if ($iRecipientID) {
        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iRecipientID);
        if ($oProfile)
            $aRecipientInfo = $oProfile->getInfo();
    } 

    // get site vars
    $sEmailNotify       = getParam('site_email_notify');
    $sSiteTitle         = getParam('site_title');
    
    // add unsubscribe link
    if (empty($aPlus['unsubscribe'])) {
        $aPlus['unsubscribe'] = '';
        if ($oAccount && (BX_EMAIL_MASS == $iEmailType || BX_EMAIL_NOTIFY == $iEmailType))
            $aPlus['unsubscribe'] = ($sLink = $oAccount->getUnsubscribeLink($iEmailType)) ? '<a href="' . BX_DOL_URL_ROOT . $sLink . '">' . _t('_sys_et_txt_unsubscribe') . '</a>' : '';
    }

    // parse template 
    if ($aPlus || $iRecipientID) {
        if(!is_array($aPlus))
            $aPlus = array();
        bx_import('BxDolEmailTemplates');
        $oEmailTemplates = BxDolEmailTemplates::getInstance();
        $sMailSubject = $oEmailTemplates->parseContent($sMailSubject, $aPlus, $iRecipientID);
        $sMailBody = $oEmailTemplates->parseContent($sMailBody, $aPlus, $iRecipientID);
    }

    // email message headers
    $sMailHeader = "From: =?UTF-8?B?" . base64_encode( $sSiteTitle ) . "?= <{$sEmailNotify}>";
    $sMailParameters = "-f{$sEmailNotify}";
    $sMailSubject = '=?UTF-8?B?' . base64_encode( $sMailSubject ) . '?=';
    $sMailHeader = "MIME-Version: 1.0\r\n" . $sMailHeader;

    // if SMPT mailer is installed and enabled - send mail throught it
    if (!$isDisableAlert && 'on' == getParam('bx_smtp_on')) { // TODO: remake to use alert: before_send_mail
        return BxDolService::call('bx_smtp', 'send', array($sRecipientEmail, $sMailSubject, $sMailBody, $sMailHeader, $sMailParameters, 'html' == $sEmailFlag, $aRecipientInfo));
    }

    // send mail
    if( 'html' == $sEmailFlag) {
        $sMailHeader = "Content-type: text/html; charset=UTF-8\r\n" . $sMailHeader;
        $iSendingResult = mail( $sRecipientEmail, $sMailSubject, $sMailBody, $sMailHeader, $sMailParameters );
    } else {
        $sMailHeader = "Content-type: text/plain; charset=UTF-8\r\n" . $sMailHeader;
        $sMailBody = html2txt($sMailBody);
        $iSendingResult = mail( $sRecipientEmail, $sMailSubject, html2txt($sMailBody), $sMailHeader, $sMailParameters );
    }

    // system alert
    if (!$isDisableAlert) {
        bx_alert('profile', 'send_mail', (isset($aRecipientInfo['ID']) ? $aRecipientInfo['ID'] : 0), '', array(
            'email'     => $sRecipientEmail,
            'subject'   => $sMailSubject,
            'body'      => $sMailBody,
            'header'    => $sMailHeader,
            'params'    => $sMailParameters,
            'html'      => 'html' == $sEmailFlag ? true : false,
        ));
    }

    return $iSendingResult;
}

/*
 * Getting an array with Templates' Names
 */
function get_templates_array($bEnabledOnly = true) {
    bx_import('BxDolDb');
    return BxDolDb::getInstance()->getPairs("SELECT `uri`, `title` FROM `sys_modules` WHERE 1 AND `type`='" . BX_DOL_MODULE_TYPE_TEMPLATE . "'" . ($bEnabledOnly ? " AND `enabled`='1'" : ""), "uri", "title");
}

/*
 * The Function Show a Line with Templates Names
 */
function templates_select_txt() {
    $templ_choices = get_templates_array();
    $current_template = ( strlen( $_GET['skin'] ) ) ? $_GET['skin'] : $_COOKIE['skin'];

    foreach ($templ_choices as $tmpl_key => $tmpl_value) {
        if ($current_template == $tmpl_key) {
            $ReturnResult .= $tmpl_value . ' | ';
        } else {
            $sGetTransfer = bx_encode_url_params($_GET, array('skin'));
            $ReturnResult .= '<a href="' . bx_html_attribute($_SERVER['PHP_SELF']) . '?' . $sGetTransfer . 'skin=' . $tmpl_key . '">' . $tmpl_value . '</a> | ';
        }
    }
    return $ReturnResult;
}

function extFileExists( $sFileSrc ) {
    return (file_exists( $sFileSrc ) && is_file( $sFileSrc )) ? true : false;
}

function getVisitorIP() {
    $ip = "0.0.0.0";
    if( ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) && ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif( ( isset( $_SERVER['HTTP_CLIENT_IP'])) && (!empty($_SERVER['HTTP_CLIENT_IP'] ) ) ) {
        $ip = explode(".",$_SERVER['HTTP_CLIENT_IP']);
        $ip = $ip[3].".".$ip[2].".".$ip[1].".".$ip[0];
    } elseif((!isset( $_SERVER['HTTP_X_FORWARDED_FOR'])) || (empty($_SERVER['HTTP_X_FORWARDED_FOR']))) {
        if ((!isset( $_SERVER['HTTP_CLIENT_IP'])) && (empty($_SERVER['HTTP_CLIENT_IP']))) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    return $ip;
}

function genFlag( $country ) {
    return '<img src="' . genFlagUrl($country) . '" />';
}

function genFlagUrl($country) {
    bx_import('BxDolTemplate');
    return BxDolTemplate::getInstance()->getIconUrl('sys_fl_' . strtolower($country) . '.gif');
}

// print debug information ( e.g. arrays )
function echoDbg( $what, $desc = '' ) {
    if ( $desc )
        echo "<b>$desc:</b> ";
    echo "<pre>";
        print_r( $what );
    echo "</pre>\n";
}

function echoDbgLog($mWhat, $sDesc = '', $sFileName = 'debug.log') {
    $sCont =
        '--- ' . date('r') . ' (' . BX_DOL_START_TIME . ") ---\n" .
        $sDesc . "\n" .
        print_r($mWhat, true) . "\n\n\n";

    $rFile = fopen(BxDolConfig::getInstance()->get('path_dynamic', 'tmp') . $sFileName, 'a');
    fwrite($rFile, $sCont);
    fclose($rFile);
}

function clear_xss($val) {

    if ($GLOBALS['logged']['admin'])
        return $val;

    // HTML Purifier plugin
    global $oHtmlPurifier;
    require_once( BX_DIRECTORY_PATH_PLUGINS . 'htmlpurifier/HTMLPurifier.standalone.php' );
    if (!isset($oHtmlPurifier)) {

        HTMLPurifier_Bootstrap::registerAutoload();

        $oConfig = HTMLPurifier_Config::createDefault();

        $oConfig->set('HTML.SafeObject', 'true');
        $oConfig->set('Output.FlashCompat', 'true');
        $oConfig->set('HTML.FlashAllowFullScreen', 'true');

        $oConfig->set('Filter.Custom', array (new HTMLPurifier_Filter_LocalMovie()));

        $oConfig->set('HTML.DefinitionID', '1');
        $oDef = $oConfig->getHTMLDefinition(true);
        $oDef->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');

        $oHtmlPurifier = new HTMLPurifier($oConfig);
    }

    return $oHtmlPurifier->purify($val);
}

function _format_when ($iSec) {
    $s = '';

    if ($iSec>0) {
        if ($iSec < 3600) {
            $i = round($iSec/60);
            $s .= (0 == $i || 1 == $i) ? _t('_x_minute_ago', '1', '') : _t('_x_minute_ago', $i, 's');
        } else if ($iSec < 86400) {
            $i = round($iSec/60/60);
            $s .= (0 == $i || 1 == $i) ? _t('_x_hour_ago', '1', '') : _t('_x_hour_ago', $i, 's');
        } else {
            $i = round($iSec/60/60/24);
            $s .= (0 == $i || 1 == $i) ? _t('_x_day_ago', '1', '') : _t('_x_day_ago', $i, 's');
        }
    } else {
        if ($iSec > -3600) {
            $i = round($iSec/60);
            $s .= (0 == $i || 1 == $i) ? _t('_in_x_minute', '1') : _t('_in_x_minute', -$i, 's');
        } else if ($iSec > -86400) {
            $i = round($iSec/60/60);
            $s .= (0 == $i || 1 == $i) ? _t('_in_x_hour', '1') : _t('_in_x_hour', -$i, 's');
        } elseif ($iSec < -86400) {
            $i = round($iSec/60/60/24);
            $s .= (0 == $i || 1 == $i) ? _t('_in_x_day', '1') : _t('_in_x_day', -$i, 's');
        }
    }
    return $s;
}

function defineTimeInterval($iTime) {
    $iTime = time() - (int)$iTime;
    $sCode = _format_when($iTime);
    return $sCode;
}

function execSqlFile($sFileName) {
    if (! $f = fopen($sFileName, "r"))
        return false;

    db_res( "SET NAMES 'utf8'" );

    $s_sql = "";
    while ( $s = fgets ( $f, 10240) ) {
        $s = trim( $s ); //Utf with BOM only

        if( !strlen( $s ) ) continue;
        if ( mb_substr( $s, 0, 1 ) == '#'  ) continue; //pass comments
        if ( mb_substr( $s, 0, 2 ) == '--' ) continue;

        $s_sql .= $s;

        if ( mb_substr( $s, -1 ) != ';' ) continue;

        db_res( $s_sql );
        $s_sql = "";
    }

    fclose($f);
    return true;
}

function replace_full_uris( $text ) {
    $text = preg_replace_callback( '/([\s\n\r]src\=")([^"]+)(")/', 'replace_full_uri', $text );
    return $text;
}

function replace_full_uri( $matches ) {
    if( substr( $matches[2], 0, 7 ) != 'http://' and substr( $matches[2], 0, 6 ) != 'ftp://' )
        $matches[2] = BX_DOL_URL_ROOT . $matches[2];

    return $matches[1] . $matches[2] . $matches[3];
}

//--------------------------------------- friendly permalinks --------------------------------------//
//------------------------------------------- main functions ---------------------------------------//
function uriGenerate ($s, $sTable, $sField, $sEmpty = '-') {
    $s = uriFilter($s, $sEmpty);
    if(uriCheckUniq($s, $sTable, $sField)) 
        return $s;

    // cut off redundant part
    if(get_mb_len($s) > 240)
        $s = get_mb_substr($s, 0, 240);

    // try to add date
    $s .= '-' . date('Y-m-d');
    if(uriCheckUniq($s, $sTable, $sField)) 
        return $s;

    // try to add number
    for($i = 0 ; $i < 999 ; ++$i)
        if(uriCheckUniq($s . '-' . $i, $sTable, $sField))
            return ($s . '-' . $i);

    return rand(0, 999999999);
}

function uriFilter ($s, $sEmpty = '-') {
    bx_import('BxTemplConfig');
    if (BxTemplConfig::getInstance()->bAllowUnicodeInPreg)
        $s = get_mb_replace ('/[^\pL^\pN]+/u', '-', $s); // unicode characters
    else
        $s = get_mb_replace ('/([^\d^\w]+)/u', '-', $s); // latin characters only

    $s = get_mb_replace ('/([-^]+)/', '-', $s);
    return !$s ? $sEmpty : trim($s, " -");
}

function uriCheckUniq ($s, $sTable, $sField) {
    bx_import('BxDolDb');
    $oDb = BxDolDb::getInstance();

    $sSql = $oDb->prepare("SELECT 1 FROM `$sTable` WHERE `$sField`=? LIMIT 1", $s);
    return !$oDb->query($sSql);
}

function get_mb_replace ($sPattern, $sReplace, $s) {
    return preg_replace ($sPattern, $sReplace, $s);
}

function get_mb_len ($s) {
    return mb_strlen($s);
}

function get_mb_substr ($s, $iStart, $iLen) {
    return mb_substr ($s, $iStart, $iLen);
}

function bx_mb_substr_replace($s, $sReplace, $iPosStart, $iLength) {
    return mb_substr($s, 0, $iPosStart) . $sReplace . mb_substr($s, $iPosStart + $iLength);
} 

function bx_mb_strpos ($s, $sReplacement, $iStart = 0) {
    return mb_strpos($s, $sReplacement, $iStart);
}

/**
 * Block user IP
 *
 * @param $sIP mixed
 * @param $iExpirationInSec integer
 * @param $sComment string
 * @return void
 */
function bx_block_ip($mixedIP, $iExpirationInSec = 86400, $sComment = '') {

    if (preg_match('/^[0-9]+$/', $mixedIP))
        $iIP = $mixedIP;
    else
        $iIP = sprintf("%u", ip2long($sIP));

    $iExpirationInSec = time() + (int)$iExpirationInSec;

    $oDb = BxDolDb::getInstance();
    $sQuery = $oDb->prepare("SELECT ID FROM `sys_ip_list` WHERE `From` = ? AND `To` = ? LIMIT 1", $iIP, $iIP);
    if (!$oDb->getOne($sQuery)) {
        $sQuery = $oDb->prepare("INSERT INTO `sys_ip_list` SET `From` = ?, `To` = ?, `Type` = 'deny', `LastDT` = ?, `Desc` = ?", $iIP, $iIP, $iExpirationInSec, $sComment);
        return $oDb->res($sQuery);
    }
    return false;
}

function bx_is_ip_dns_blacklisted($sCurIP = '', $sType = '') {

    if (defined('BX_DOL_CRON_EXECUTE'))
        return false;

    if (!$sCurIP)
        $sCurIP = getVisitorIP();

    if (bx_is_ip_whitelisted($sCurIP))
        return false;

    $o = bx_instance('BxDolDNSBlacklists');
    if (BX_DOL_DNSBL_POSITIVE == $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_SPAMMERS, $sCurIP) && BX_DOL_DNSBL_POSITIVE != $o->dnsbl_lookup_ip(BX_DOL_DNSBL_CHAIN_WHITELIST, $sCurIP))
    {
        $o->onPositiveDetection ($sCurIP, $sType);
        return true;
    }

    return false;
}

function bx_is_ip_whitelisted($sCurIP = '') {

    if (defined('BX_DOL_CRON_EXECUTE'))
        return true;

    $iIPGlobalType = (int)getParam('ipListGlobalType');
    if ($iIPGlobalType != 1 && $iIPGlobalType != 2) // 0 - disabled
        return false;

    if (!$sCurIP)
        $sCurIP = getVisitorIP();
    $iCurIP = sprintf("%u", ip2long($sCurIP));
    $iCurrTume = time();

    return db_value("SELECT `ID` FROM `sys_ip_list` WHERE `Type` = 'allow' AND `LastDT` > $iCurrTume AND `From` <= '$iCurIP' AND `To` >= '$iCurIP' LIMIT 1") ? true : false;
}

function bx_is_ip_blocked($sCurIP = '') {

    if (defined('BX_DOL_CRON_EXECUTE'))
        return false;

    $iIPGlobalType = (int)getParam('ipListGlobalType');
    if ($iIPGlobalType != 1 && $iIPGlobalType != 2) // 0 - disabled
        return false;

    if (!$sCurIP)
        $sCurIP = getVisitorIP();
    $iCurIP = sprintf("%u", ip2long($sCurIP));
    $iCurrTume = time();

    if (bx_is_ip_whitelisted($sCurIP))
        return false;

    $isBlocked = db_value("SELECT `ID` FROM `sys_ip_list` WHERE `Type` = 'deny' AND `LastDT` > $iCurrTume AND `From` <= '$iCurIP' AND `To` >= '$iCurIP' LIMIT 1");
    if ($isBlocked)
        return true;

    // 1 - all allowed except listed
    // 2 - all blocked except listed
    return $iIPGlobalType == 2 ? true : false;
}

/**
 *  spam checking function
 *  @param $s content to check for spam
 *  @param $isStripSlashes slashes parameter:
 *          BX_SLASHES_AUTO - automatically detect magic_quotes_gpc setting
 *          BX_SLASHES_NO_ACTION - do not perform any action with slashes
 *  @return true if spam detected
 */
function bx_is_spam ($val, $isStripSlashes = BX_SLASHES_AUTO) {

    if (defined('BX_DOL_CRON_EXECUTE'))
        return false;

    if (isAdmin())
        return false;

    if (bx_is_ip_whitelisted($sCurIP))
        return false;

    if (get_magic_quotes_gpc() && $isStripSlashes == BX_SLASHES_AUTO)
        $val = stripslashes($val);

    $bRet = false;
    if ('on' == getParam('sys_uridnsbl_enable')) {
        $oBxDolDNSURIBlacklists = bx_instance('BxDolDNSURIBlacklists');
        if ($oBxDolDNSURIBlacklists->isSpam($val)) {
            $oBxDolDNSURIBlacklists->onPositiveDetection($val);
            $bRet = true;
        }
    }

    if ('on' == getParam('sys_akismet_enable')) {
        $oBxDolAkismet = bx_instance('BxDolAkismet');
        if ($oBxDolAkismet->isSpam($val)) {
            $oBxDolAkismet->onPositiveDetection($val);
            $bRet = true;
        }
    }

    if ($bRet && 'on' == getParam('sys_antispam_report')) {

        $iProfileId = getLoggedId();
        $aPlus = array(
            'SpammerUrl' => getProfileLink($iProfileId),
            'SpammerNickName' => getNickName($iProfileId),
            'Page' => htmlspecialchars_adv($_SERVER['PHP_SELF']),
            'Get' => print_r($_GET, true),
            'SpamContent' => htmlspecialchars_adv($val),
        );

        bx_import('BxDolEmailTemplates');
        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('t_SpamReportAuto', $aPlus);
        if (!$aTemplate)
            trigger_error('Email template or translation missing: t_SpamReportAuto', E_USER_ERROR);

        sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);
    }

    if ($bRet && 'on' == getParam('sys_antispam_block'))
        return true;

    return false;
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

/**
** @description : function will create cache file with all SQL queries ;
** @return        :
*/
function genSiteStatCache() {

    $sqlQuery = "SELECT `Name` as `name`,
                        `Title` as `capt`,
                        `UserQuery` as `query`,
                        `UserLink` as `link`,
                        `IconName` as `icon`,
                        `AdminQuery` as `adm_query`,
                           `AdminLink` as `adm_link`
                        FROM `sys_stat_site`
                        ORDER BY `StatOrder` ASC, `ID` ASC";

    $rData = db_res($sqlQuery);

    $sLine = "return array( \n";
    while ($aVal = mysql_fetch_assoc($rData)) {
        $sLine .= genSiteStatFile($aVal);
    }
    $sLine = rtrim($sLine, ",\n")."\n);";

    $aResult = eval($sLine);

    $oCache = BxDolDb::getInstance()->getDbCacheObject();
    return $oCache->setData (BxDolDb::getInstance()->genDbCacheKey('sys_stat_site'), $aResult);
}

/**
 * Function will cute the parameter from received string;
 * remove received parameter from 'GET' query ;
 *
 * @param        : $aExceptNames (string) - name of unnecessary parameter;
 * @return       : cleared string;
 */
function getClearedParam( $sExceptParam, $sString ) {
    return preg_replace( "/(&amp;|&){$sExceptParam}=([a-z0-9\_\-]{1,})/i",'', $sString);
}

/**
 * import class file, it detect class path by its prefix or module array
 *
 * @param $sClassName - full class name or class postfix in a case of module class
 * @param $aModule - module array or true to get module array from global variable
 */
function bx_import($sClassName, $aModule = array()) {
    if (class_exists($sClassName))
        return;

    if ($aModule) {
        $a = (true === $aModule) ? $GLOABLS['aModule'] : $aModule;
        if (class_exists($a['class_prefix'] . $sClassName))
            return;
        require_once (BX_DIRECTORY_PATH_MODULES . $a['path'] . 'classes/' . $a['class_prefix'] . $sClassName . '.php');
    }

    if (0 == strncmp($sClassName, 'BxDol', 5)) {
        if (0 == strncmp($sClassName, 'BxDolStudio', 11))
            require_once(BX_DOL_DIR_STUDIO_CLASSES . $sClassName . '.php');
        else 
            require_once(BX_DIRECTORY_PATH_CLASSES . $sClassName . '.php');
        return;
    }

    if (0 == strncmp($sClassName, 'BxBase', 6)) {
    	if(0 == strncmp($sClassName, 'BxBaseStudio', 12))
            require_once(BX_DOL_DIR_STUDIO_BASE . 'scripts/' . $sClassName . '.php');
        else
            require_once(BX_DIRECTORY_PATH_BASE . 'scripts/' . $sClassName . '.php');
        return;
    }

    if (0 == strncmp($sClassName, 'BxTempl', 7) && !class_exists($sClassName)) {
    	if(0 == strncmp($sClassName, 'BxTemplStudio', 13)) {
    	    bx_import('BxDolStudioTemplate');
    	    $sPath = BX_DIRECTORY_PATH_MODULES . BxDolStudioTemplate::getInstance()->getPath() . 'data/template/studio/scripts/' . $sClassName . '.php';
    	}
        else {
            bx_import('BxDolTemplate');
            $sPath = BX_DIRECTORY_PATH_MODULES . BxDolTemplate::getInstance()->getPath() . 'data/template/system/scripts/' . $sClassName . '.php';
        }

        if(file_exists($sPath)) {
            require_once($sPath);
            return;
        }

        echo "<b>Fatal error:</b> Class (" . $sClassName . ") not found.";
        exit;
    }
}

/**
 * Gets an instance of class pathing necessary parameters if it's necessary.
 *
 * @param string $sClassName class name.
 * @param array $aParams an array of parameters to be pathed to the constructor of the class.
 * @param array $aModule an array with module description. Is used when the requested class is located in some module.
 * @return unknown
 */
function bx_instance($sClassName, $aParams = array(), $aModule = array()) {
    if(isset($GLOBALS['bxDolClasses'][$sClassName]))
        return $GLOBALS['bxDolClasses'][$sClassName];
    else {
        bx_import((empty($aModule) ? $sClassName : str_replace($aModule['class_prefix'], '', $sClassName)), $aModule);

        if(empty($aParams))
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        else {
            $sParams = "";
            foreach($aParams as $mixedKey => $mixedValue)
                $sParams .= "\$aParams[" . $mixedKey . "], ";
            $sParams = substr($sParams, 0, -2);

            $GLOBALS['bxDolClasses'][$sClassName] = eval("return new " . $sClassName . "(" . $sParams . ");"); // TODO: remake without eval
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }
}


/**
 * Escapes string/array ready to pass to js script with filtered symbols like ', " etc
 *
 * @param $mixedInput - string/array which should be filtered
 * @param $iQuoteType - string escaping method: BX_ESCAPE_STR_AUTO(default), BX_ESCAPE_STR_APOS or BX_ESCAPE_STR_QUOTE
 * @return converted string / array
 */
function bx_js_string ($mixedInput, $iQuoteType = BX_ESCAPE_STR_AUTO) {
    $aUnits = array(
        "\n" => "\\n",
        "\r" => "",
    );
    if (BX_ESCAPE_STR_APOS == $iQuoteType) {
        $aUnits["'"] = "\\'";
        $aUnits['<script'] = "<scr' + 'ipt";
        $aUnits['</script>'] = "</scr' + 'ipt>";
    } elseif (BX_ESCAPE_STR_QUOTE == $iQuoteType) {
        $aUnits['"'] = '\\"';
        $aUnits['<script'] = '<scr" + "ipt';
        $aUnits['</script>'] = '</scr" + "ipt>';
    } else {
        $aUnits['"'] = '&quote;';
        $aUnits["'"] = '&apos;';
        $aUnits["<"] = '&lt;';
        $aUnits[">"] = '&gt;';
    }
    return str_replace(array_keys($aUnits), array_values($aUnits), $mixedInput);
}

/**
 * Return input string/array ready to pass to html attribute with filtered symbols like ', " etc
 *
 * @param mixed $mixedInput - string/array which should be filtered
 * @return converted string / array
 */
function bx_html_attribute ($mixedInput, $iQuoteType = BX_ESCAPE_STR_AUTO) {

    $aUnits = array ();
    if (BX_ESCAPE_STR_APOS == $iQuoteType)
        $aUnits["'"] = "\\'";
    elseif (BX_ESCAPE_STR_QUOTE == $iQuoteType)
        $aUnits['"'] = '\\"';
    else
        $aUnits = array("\"" => "&quot;", "'" => "&apos;");

    return str_replace(array_keys($aUnits), array_values($aUnits), $mixedInput);
}

/**
 * Escapes string/array ready to pass to php script with filtered symbols like ', " etc
 *
 * @param mixed $mixedInput - string/array which should be filtered
 * @return converted string / array
 */
function bx_php_string_apos ($mixedInput) {
    return str_replace("'", "\\'", $mixedInput);
}
function bx_php_string_quot ($mixedInput) {
    return str_replace('"', '\\"', $mixedInput);
}

/**
 * Gets file contents by URL.
 *
 * @param string $sFileUrl - file URL to be read.
 * @param array $aParams - an array of parameters to be pathed with URL.
 * @return string the file's contents.
 */
function bx_file_get_contents($sFileUrl, $aParams = array(), $bChangeTimeout = false) {

    if ($aParams)
        $sFileUrl = bx_append_url_params($sFileUrl, $aParams);

    $sResult = '';
    if(function_exists('curl_init')) {
        $rConnect = curl_init();

        curl_setopt($rConnect, CURLOPT_URL, $sFileUrl);
        curl_setopt($rConnect, CURLOPT_HEADER, 0);
        curl_setopt($rConnect, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rConnect, CURLOPT_FOLLOWLOCATION, 1);

        if ($bChangeTimeout) {
            curl_setopt($rConnect, CURLOPT_CONNECTTIMEOUT, 3);
            curl_setopt($rConnect, CURLOPT_TIMEOUT, 3);
        }

        $sAllCookies = '';
        foreach($_COOKIE as $sKey=>$sValue){
            $sAllCookies .= $sKey."=".$sValue.";";
        }
        curl_setopt($rConnect, CURLOPT_COOKIE, $sAllCookies);

        $sResult = curl_exec($rConnect);
        curl_close($rConnect);
    } else {

        $iSaveTimeout = false;
        if ($bChangeTimeout) {
            $iSaveTimeout = ini_get('default_socket_timeout');
            ini_set('default_socket_timeout', 3);
        }

        $sResult = @file_get_contents($sFileUrl);

        if ($bChangeTimeout && false !== $iSaveTimeout) {
            ini_set('default_socket_timeout', $iSaveTimeout);
        }
    }

    return $sResult;
}

function bx_append_url_params ($sUrl, $mixedParams) {
    $sParams = false == strpos($sUrl, '?') ? '?' : '&';
        
    if (is_array($mixedParams)) {
        foreach($mixedParams as $sKey => $sValue)
            $sParams .= $sKey . '=' . $sValue . '&';
        $sParams = substr($sParams, 0, -1);
    } else {
        $sParams .= $mixedParams;
    }    
    return $sUrl . $sParams;
}

/**
 * perform write log into 'tmp/log.txt' (for any debug development)
 *
 * @param $sNewLineText - New line debug text
  */
function writeLog($sNewLineText = 'test') {
    $sFileName = BX_DIRECTORY_PATH_ROOT . 'tmp/log.txt';

    if (is_writable($sFileName)) {
        if (! $vHandle = fopen($sFileName, 'a')) {
             echo "Unable to open ({$sFileName})";
        }
        if (fwrite($vHandle, $sNewLineText . "\r\n") === FALSE) {
            echo "Unable write to ({$sFileName})";
        }
        fclose($vHandle);

    } else {
        echo "{$sFileName} is not writeable";
    }
}

function getLinkSet ($sLinkString, $sUrlPrefix, $sDivider = ';,', $bUriConvert = false) {
    $aSet = preg_split( '/['.$sDivider.']/', $sLinkString, 0, PREG_SPLIT_NO_EMPTY);
    $sFinalSet = '';

    foreach ($aSet as $sKey) {
        $sLink =  $sUrlPrefix . urlencode($bUriConvert ? title2uri($sKey) : $sKey);
        $sFinalSet .= '<a href="' . $sUrlPrefix . urlencode(title2uri($sKey)) . '">' . $sKey . '</a> ';
    }

    return trim($sFinalSet, ' ');
}

// TODO: move to files modules - it is used only there
function getRelatedWords (&$aInfo) {
    $sString = implode(' ', $aInfo);
    $aRes = array_unique(explode(' ', $sString));
    $sString = implode(' ', $aRes);
    return addslashes($sString);
}

// TODO: move to sites module - it is used only there
function getSiteInfo($sSourceUrl)
{
    $aResult = array();
    $sContent = bx_file_get_contents($sSourceUrl);

    if (strlen($sContent))
    {
        preg_match("/<title>(.*)<\/title>/", $sContent, $aMatch);
        $aResult['title'] = $aMatch[1];

        preg_match("/<meta.*name[='\" ]+description['\"].*content[='\" ]+(.*)['\"].*><\/meta>/", $sContent, $aMatch);
        $aResult['description'] = $aMatch[1];
    }

    return $aResult;
}

// calculation ini_get('upload_max_filesize') in bytes as example
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    $val = (int)$val;
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'k':
            $val *= 1024;
            break;
        case 'm':
            $val *= 1024 * 1024;
            break;
        case 'g':
            $val *= 1024 * 1024 * 1024;
            break;
    }
    return $val;
}

// Generate Random Password
function genRndPwd($iLength = 8, $bSpecialCharacters = true) {
    $sPassword = '';
    $sChars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";

    if($bSpecialCharacters === true)
        $sChars .= "!?=/&+,.";

    srand((double)microtime()*1000000);
    for($i = 0; $i < $iLength; $i++) {
        $x = mt_rand(0, strlen($sChars) -1);
        $sPassword .= $sChars{$x};
    }

    return $sPassword;
}

// Generate Random Salt for Password encryption
function genRndSalt() {
    return genRndPwd(8, true);
}

// Encrypt User Password
function encryptUserPwd($sPwd, $sSalt) {
    return sha1(md5($sPwd) . $sSalt);
}

// Advanced stripslashes. Strips strings and arrays
function stripslashes_adv($s) {
    if (is_string($s))
        return stripslashes($s);
    elseif (is_array($s)) {
        foreach ($s as $k => $v) {
            $s[$k] = stripslashes($v);
        }
        return $s;
    } else
        return $s;
}

function bx_get ($sName) {
    if (isset($_GET[$sName]))
        return $_GET[$sName];
    elseif (isset($_POST[$sName]))
        return $_POST[$sName];
    else
        return false;
}

function bx_encode_url_params ($a, $aExcludeKeys = array (), $aOnlyKeys = false) {
    $s = '';
    foreach ($a as $sKey => $sVal) {
        if (in_array($sKey, $aExcludeKeys))
            continue;
        if (false !== $aOnlyKeys && !in_array($sKey, $aOnlyKeys))
            continue;
        if (is_array($sVal)) {
            foreach ($sVal as $sSubVal) {
                $s .= rawurlencode($sKey) . '[]=' . rawurlencode(is_array($sSubVal) ? 'array' : $sSubVal) . '&';
            }
        } else {
            $s .= rawurlencode($sKey) . '=' . rawurlencode($sVal) . '&';
        }
    }
    return $s;
}

/**
 * Convert array to attributes string
 *
 * Example:
 * @code
 * $a = array('name' => 'test', 'value' => 5);
 * $s = bx_convert_array2attrs($a);
 * echo $s; // outputs: name="test" value="5"
 * @endcode
 *
 * @param $a - array of attributes
 * @param $sClasses - classes to merge with 'class' attribute
 * @param $sStyles - styles to merge with 'style' attribute
 * @return string
 */
function bx_convert_array2attrs ($a, $sClasses = false, $sStyles = false) {
    $sRet = '';

    if (!$a || !is_array($a))
        $a = array();

    if ($sClasses) {
        $sClasses = trim($sClasses);
        $a['class'] = $sClasses . (!empty($a['class']) ? ' ' . $a['class'] : '');
    }

    if ($sStyles) {
        $sStyles = trim($sStyles);
        if (';' != $sStyles[strlen($sStyles)-1])
            $sStyles .= ';';
        $a['style'] = $sStyles . (!empty($a['style']) ? ' ' . $a['style'] : '');
    }

    foreach ($a as $sKey => $sValue) {
        if(is_null($sValue)) // pass NULL values
            continue;

        $sValueC = bx_html_attribute($sValue, BX_ESCAPE_STR_QUOTE);

        $sRet .= " $sKey=\"$sValueC\"";
    }

    return $sRet;
}

function bx_unicode_urldecode($s) {

    preg_match_all('/%u([[:alnum:]]{4})/', $s, $a);

    foreach ($a[1] as $uniord)
    {
        $dec = hexdec($uniord);
        $utf = '';

        if ($dec < 128)
        {
            $utf = chr($dec);
        }
        else if ($dec < 2048)
        {
            $utf = chr(192 + (($dec - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        }
        else
        {
            $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
            $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        }

        $s = str_replace('%u'.$uniord, $utf, $s);
    }

    return urldecode($s);
}

/**
 * Raise an alert
 * @param string $sType - system type
 * @param string $sAction - system action
 * @param int $iObjectId - object id
 * @param int $iSenderId - sender (action's author) profile id, if it is false - then currectly logged in profile id is used
 */
function bx_alert($sUnit, $sAction, $iObjectId, $iSender = false, $aExtras = array()) {
    $o = new BxDolAlerts($sUnit, $sAction, $iObjectId, $iSender, $aExtras);
    $o->alert();
}

function getSitesArray ($sLink) {

    $aSites = $GLOBALS['MySQL']->fromCache ('sys_shared_sites', 'getAllWithKey', "SELECT `ID` as `id`, `URL` as `url`, `Icon` as `icon`, `Name` FROM `sys_shared_sites`", 'Name');

    $sLink = htmlentities(($sLink));

    foreach ($aSites as $sKey => $aValue)
        $aSites[$sKey]['url'] .= $sLink;

    return $aSites;
}


/** @} */ 

