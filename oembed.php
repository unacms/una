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

// used in oembed integration
if (bx_get('l')){
    
    $aLinks = bx_get('l');

    bx_import('BxDolEmbed');
    $oEmbed = BxDolEmbed::getObjectInstance('sys_oembed');
    echoJson($oEmbed->parseLinks($aLinks));
}

// embedded representation of a URL on third party sites
if (bx_get('url')){
    $sUrl = urldecode(bx_get('url'));
    $aUrl = parse_url($sUrl);
    $aUri = explode('/', $aUrl['path']);
    $aParams = [];
    parse_str($aUrl['query'], $aParams);
    if (!$aUri || empty($aUri[2]))
        echo 'no';
    
    $sContentInfoObject = BxDolPageQuery::getContentInfoObjectNameByURI($aUri[2]);
    $oContentInfo = BxDolContentInfo::getObjectInstance($sContentInfoObject);
    
    $sTitle = $oContentInfo->getContentTitle($aParams['id']);
    $iAuthor = $oContentInfo->getContentAuthor($aParams['id']);
    $oProfile = BxDolProfile::getInstance($iAuthor);
    $sAuthorName = $oProfile->getDisplayName();
    $sAuthorUrl = $oProfile->getUrl();
    $sHtml = $oContentInfo->getContentEmbed($aParams['id']);
    $sThumb = $oContentInfo->getContentThumb($aParams['id']);
    
    if (bx_get('format') == 'json'){
        $aResult = [
            'version' => '1.0',
            'type' => 'rich',
            'title' => $sTitle,
            'url' => $sUrl,
            'author_name' => $sAuthorName,
            'author_url' => $sAuthorUrl,
            'url' => $sUrl,
            'provider_name' => getParam('site_title'),
            'provider_url' => BX_DOL_URL_ROOT,
            'html' => $sHtml,
        ];
        
        if ($sThumb){
             $aResult['thumbnail_url'] = $sThumb;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode($aResult);
    }
}