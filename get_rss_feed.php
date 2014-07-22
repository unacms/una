<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

require_once('./inc/header.inc.php');

$mixedBlockId = bx_process_input(bx_get('ID'));

$aPredefinedRssFeeds = array (
    'boonex_news' => 'http://www.boonex.com/unity/blog/featured_posts/?rss=1',
    'boonex_version' => 'http://rss.boonex.com/',
    'boonex_unity_market' => 'http://www.boonex.com/unity/extensions/latest/?rss=1',
    'boonex_unity_lang_files' => 'http://www.boonex.com/unity/extensions/tag/translations&rss=1',
    'boonex_unity_market_templates' => 'http://www.boonex.com/unity/extensions/tag/templates&rss=1',
    'boonex_unity_market_featured' => 'http://www.boonex.com/unity/extensions/featured_posts?rss=1',
);

$sCont = "";

$aMatches = array();
$sPattern = "/((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*\.)+(aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel|[a-z]{2})|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&amp;]*)?)?(#[a-z][a-z0-9_]*)?/";
if(preg_match($sPattern, $mixedBlockId, $aMatches))
	$sCont = $aMatches[0];
else if(isset($aPredefinedRssFeeds[$mixedBlockId]))
    $sCont = $aPredefinedRssFeeds[$mixedBlockId];
else {
    bx_import('BxDolPageQuery');
    $oPageQuery = new BxDolPageQuery(array());
    $sCont = $oPageQuery->getPageBlockContent($mixedBlockId);

    if (!$sCont)
        exit;
}

list($sUrl) = explode( '#', $sCont );
$sUrl = str_replace('{SiteUrl}', BX_DOL_URL_ROOT, $sUrl);

$iMemID = bx_process_input(bx_get('member'), BX_DATA_INT);
if ($iMemID) {
    $aMember = getProfileInfo( $iMemID );
    $sUrl = str_replace( '{NickName}', $aMember['NickName'], $sUrl );
}

header('Content-Type: text/xml; charset=utf-8');
echo bx_file_get_contents($sUrl . (defined('BX_PROFILER') && BX_PROFILER && 0 == strncmp(BX_DOL_URL_ROOT, $sUrl, strlen(BX_DOL_URL_ROOT)) ? '&bx_profiler_disable=1' : ''));

/** @} */
