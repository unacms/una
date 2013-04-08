<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

/**
 * design box with content only - no borders, no background, no caption
 * @see DesignBoxContent
 */
define('BX_DB_CONTENT_ONLY', 0);

/**
 * default design box with content, borders and caption
 * @see DesignBoxContent
 */
define('BX_DB_DEF', 1);

/**
 * just empty design box, without anything
 * @see DesignBoxContent
 */
define('BX_DB_EMPTY', 2);

/**
 * design box with content, like BX_DB_DEF but without caption
 * @see DesignBoxContent
 */
define('BX_DB_NO_CAPTION', 3);

/**
 * design box with content only wrapped with default padding - no borders, no background, no caption
 * it can be used to just wrap content with default padding
 * @see DesignBoxContent
 */
define('BX_DB_PADDING_CONTENT_ONLY', 10);

/**
 * default design box with content wrapped with default padding, borders and caption
 * @see DesignBoxContent
 */
define('BX_DB_PADDING_DEF', 11);

/**
 * design box with content wrapped with default padding, like BX_DB_DEF but without caption
 * @see DesignBoxContent
 */
define('BX_DB_PADDING_NO_CAPTION', 13);

/**
 * Display "design box" HTML code
 *
 * @see BxBaseFunctions::DesignBoxContent
 *
 * @see BX_DB_CONTENT_ONLY
 * @see BX_DB_DEF
 * @see BX_DB_EMPTY
 * @see BX_DB_NO_CAPTION
 * @see BX_DB_PADDING_CONTENT_ONLY
 * @see BX_DB_PADDING_DEF
 * @see BX_DB_PADDING_NO_CAPTION
 */
function DesignBoxContent ($sTitle, $sContent, $iTemplateNum = BX_DB_DEF, $mixedMenu = false) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->designBoxContent ($sTitle, $sContent, $iTemplateNum, $mixedMenu);
}

/**
 * DEPRECATED
 * Put top code for the page
 **/
function PageCode($oTemplate = null) {
    echo "DEPRECATED: function PageCode, use BxDolTemplate::getPageCode instead";
    if (empty($oTemplate))
       $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->getPageCode();
}

/**
 * Use this function in pages if you want to not cache it.
 **/
function send_headers_page_changed() {
    $now = gmdate('D, d M Y H:i:s') . ' GMT';

    header("Expires: $now");
    header("Last-Modified: $now");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
}


function getFieldValues( $sField, $sUseLKey = 'LKey' ) {

    require_once(BX_DIRECTORY_PATH_INC . "prof.inc.php");

    global $aPreValues;

    $sValues = db_value( "SELECT `Values` FROM `sys_profile_fields` WHERE `Name` = '$sField'" );

    if( substr( $sValues, 0, 2 ) == '#!' ) {
        //predefined list
        $sKey = substr( $sValues, 2 );

        $aValues = array();

        $aMyPreValues = $aPreValues[$sKey];
        if( !$aMyPreValues )
            return $aValues;

        foreach( $aMyPreValues as $sVal => $aVal ) {
            $sMyUseLKey = $sUseLKey;
            if( !isset( $aMyPreValues[$sVal][$sUseLKey] ) )
                $sMyUseLKey = 'LKey';

            $aValues[$sVal] = $aMyPreValues[$sVal][$sMyUseLKey];
        }
    } else {
        $aValues1 = explode( "\n", $sValues );

        $aValues = array();
        foreach( $aValues1 as $iKey => $sValue )
            $aValues[$sValue] = "_$sValue";
    }

    return $aValues;
}

function get_member_thumbnail( $ID, $float, $bGenProfLink = false, $sForceSex = 'visitor', $aOnline = array()) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->getMemberThumbnail($ID, $float, $bGenProfLink, $sForceSex, true, 'medium', $aOnline);
}

function get_member_icon( $ID, $float = 'none', $bGenProfLink = false ) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->getMemberIcon( $ID, $float, $bGenProfLink );
}

function MsgBox($sText, $iTimer = 0) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->msgBox($sText, $iTimer);
}

function LoadingBox($sName) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->loadingBox($sName);
}

function PopupBox($sName, $sTitle, $sContent, $isHiddenByDefault = false) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->popupBox($sName, $sTitle, $sContent, $isHiddenByDefault);
}

function getPromoImagesArray() {
    $sPromoPath = BxDolConfig::getInstance()->get('path_dynamic', 'rpr_images_promo');

    $aAllowedExt = array('jpg' => 1, 'png' => 1, 'gif' => 1, 'jpeg' => 1);
    $aFiles = array();
    $rDir = opendir($sPromoPath);
    if( $rDir ) {
        while(($sFile = readdir($rDir)) !== false) {
            if($sFile == '.' or $sFile == '..' or !is_file($sPromoPath . $sFile))
                continue;
            $aPathInfo = pathinfo($sFile);
            $sExt = strtolower($aPathInfo['extension']);
            if (isset($aAllowedExt[$sExt])) {
                $aFiles[] = $sFile;
            }
        }
        closedir( $rDir );
    }
    shuffle( $aFiles );
    return $aFiles;
}

function getTemplateIcon( $sFileName ) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->getTemplateIcon($sFileName);
}

function getTemplateImage( $sFileName ) {
    bx_import('BxTemplFunctions');
    return BxTemplFunctions::getInstance()->getTemplateImage($sFileName);
}

function getVersionComment() {
    $aVer = explode( '.', BX_DOL_VERSION );

    // version output made for debug possibilities.
    // randomizing made for security issues. do not change it...
    $aVerR[0] = $aVer[0];
    $aVerR[1] = rand( 0, 100 );
    $aVerR[2] = $aVer[1];
    $aVerR[3] = rand( 0, 100 );
    $aVerR[4] = BX_DOL_BUILD;

    //remove leading zeros
    while( $aVerR[4][0] === '0' )
        $aVerR[4] = substr( $aVerR[4], 1 );

    return '<!-- ' . implode( ' ', $aVerR ) . ' -->';
}

// ----------------------------------- site statistick functions --------------------------------------//

function getSiteStatBody($aVal, $sMode = '') {
    $sLink = strlen($aVal['link']) > 0 ? '<a href="'.BX_DOL_URL_ROOT.$aVal['link'].'">{iNum} '._t('_'.$aVal['capt']).'</a>' : '{iNum} '._t('_'.$aVal['capt']) ;
    if ( $sMode != 'admin' ) {
        $sBlockId = '';
        $iNum = strlen($aVal['query']) > 0 ? db_value($aVal['query']) : 0;
    } else {
        $sBlockId = "id='{$aVal['name']}'";
        $iNum  = strlen($aVal['adm_query']) > 0 ? db_value($aVal['adm_query']) : 0;
        if ( strlen($aVal['adm_link']) > 0 ) {
            if( substr( $aVal['adm_link'], 0, strlen( 'javascript:' ) ) == 'javascript:' ) {
                $sHref = 'javascript:void(0);';
                $sOnclick = 'onclick="' . $aVal['adm_link'] . '"';
            } else {
                $sHref = $aVal['adm_link'];
                $sOnclick = '';
            }
            $sLink = '<a href="'.$sHref.'" '.$sOnclick.'>{iNum} '._t('_'.$aVal['capt']).'</a>';
        } else {
            $sLink = '{iNum} '._t('_'.$aVal['capt']);
        }
    }

    $sLink = str_replace('{iNum}', $iNum, $sLink);
    $sCode =
    '
        <div class="siteStatUnit" '. $sBlockId .'>
            <img src="' . getTemplateIcon($aVal['icon']) . '" alt="" />
                ' . $sLink . '
        </div>
    ';

    return $sCode;
}

function getSiteStatUser() {
    global $aStat;

    $oDb = BxDolDb::getInstance();
    $oCache = $oDb->getDbCacheObject();
    $aStat = $oCache->getData($oDb->genDbCacheKey('sys_stat_site'));
    if (null === $aStat) {
        genSiteStatCache();
        $aStat = $oCache->getData($oDb->genDbCacheKey('sys_stat_site'));
    }

    if( !$aStat )
        $aStat = array();

    $sCode  = '<div class="siteStatMain">';

    foreach($aStat as $aVal)
        $sCode .= getSiteStatBody($aVal);

    $sCode .= '<div class="clear_both"></div></div>';

    return $sCode;
}

function genSiteStatFile($aVal) {

    bx_import('BxTemplMenu');
    $sLink = BxTemplMenu::getInstance() -> getCurrLink($aVal['link']);
    $sLine = "'{$aVal['name']}'=>array('capt'=>'{$aVal['capt']}', 'query'=>'".addslashes($aVal['query'])."', 'link'=>'$sLink', 'icon'=>'{$aVal['icon']}'),\n";

    return $sLine;
}

function genAjaxyPopupJS($iTargetID, $sDivID = 'ajaxy_popup_result_div', $sRedirect = '') {
    $iProcessTime = 1000;

    if ($sRedirect)
       $sRedirect = "window.location = '$sRedirect';";

    $sJQueryJS = <<<EOF
<script type="text/javascript">

setTimeout( function(){
    $('#{$sDivID}_{$iTargetID}').show({$iProcessTime})
    setTimeout( function(){
        $('#{$sDivID}_{$iTargetID}').hide({$iProcessTime});
        $sRedirect
    }, 3000);
}, 500);

</script>
EOF;
    return $sJQueryJS;
}

function getBlockWidth ($iAllWidth, $iUnitWidth, $iNumElements) {
    $iAllowed = $iNumElements * $iUnitWidth;
    if ($iAllowed > $iAllWidth) {
        $iMax = (int)floor($iAllWidth / $iUnitWidth);
        $iAllowed = $iMax*$iUnitWidth;
    }
    return $iAllowed;
}

function getMemberLoginFormCode($sID = 'member_login_form', $sParams = '')
{
    trigger_error ("Replace getMemberLoginFormCode with BxDolService::call('system', 'login_form', array(), 'TemplServiceLogin')", E_USER_ERROR);
}

bx_import('BxDolAlerts');
$oZ = new BxDolAlerts('system', 'design_included', 0);
$oZ->alert();

