<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( '../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

$logged['admin'] = member_auth( 1, true, true );

$aResult = array();
switch($_POST['type']) {
    case 'all':
        //member menu
        bx_import('BxDolMemberMenu');
        $oMemberMenu = new BxDolMemberMenu();
        $oMemberMenu -> deleteMemberMenuCaches();

        // page blocks
        bx_import('BxDolPageViewAdmin');
        $oPageViewCacher = new BxDolPageViewCacher ('', '');
        $oCachePb = $oPageViewCacher->getBlocksCacheObject ();
        $aResult = clearCacheObject ($oCachePb, 'pb_');
        if($aResult['code'] != 0)
            break;

        // users
        $aResult = clearCache('user', BX_DIRECTORY_PATH_CACHE);
        if($aResult['code'] != 0)
            break;

        // DB
        $GLOBALS['MySQL']->oParams->clearCache();
        $oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();
        $aResult = clearCacheObject ($oCacheDb, 'db_');
        if($aResult['code'] != 0)
            break;

        // templates
        $oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
        $aResult = clearCacheObject($oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
        if($aResult['code'] != 0)
            break;

        // CSS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        if($aResult['code'] != 0)
            break;

        // JS
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        break;

    case 'pb':
        bx_import('BxDolPageViewAdmin');
        $oPageViewCacher = new BxDolPageViewCacher ('', '');
        $oCachePb = $oPageViewCacher->getBlocksCacheObject ();
        $aResult = clearCacheObject ($oCachePb, 'pb_');
        break;

    case 'users':
        //member menu
        bx_import('BxDolMemberMenu');
        $oMemberMenu = new BxDolMemberMenu();
        $oMemberMenu -> deleteMemberMenuCaches();

        $aResult = clearCache('user', BX_DIRECTORY_PATH_CACHE);
        break;

    case 'db':
        $GLOBALS['MySQL']->oParams->clearCache();
        $oCacheDb = $GLOBALS['MySQL']->getDbCacheObject();
        $aResult = clearCacheObject ($oCacheDb, 'db_');
        break;

    case 'template':
        $oCacheTemplates = $GLOBALS['oSysTemplate']->getTemplatesCacheObject();
        $aResult = clearCacheObject($oCacheTemplates, $GLOBALS['oSysTemplate']->_sCacheFilePrefix);
        break;

    case 'js_css':
        $aResult = clearCache($GLOBALS['oSysTemplate']->_sCssCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        if($aResult['code'] == 0)
            $aResult = clearCache($GLOBALS['oSysTemplate']->_sJsCachePrefix, BX_DIRECTORY_PATH_CACHE_PUBLIC);
        break;
}

$oJson = new Services_JSON();
echo $oJson->encode($aResult);
exit;


function clearCacheObject($oCache, $sPrefix) {
    if (!$oCache->removeAllByPrefix ($sPrefix))
        return array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));
    else
        return array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));
}

function clearCache($sPrefix, $sPath) {
    $aResult = array('code' => 0, 'message' => _t('_adm_txt_dashboard_cache_clean_success'));

    if($rHandler = opendir($sPath)) {
        while(($sFile = readdir($rHandler)) !== false)
            if(substr($sFile, 0, strlen($sPrefix)) == $sPrefix)
                @unlink($sPath . $sFile);
    }
    else
        $aResult = array('code' => 1, 'message' => _t('_adm_txt_dashboard_cache_clean_failed'));

    return $aResult;
}
?>
