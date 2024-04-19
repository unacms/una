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

$aLinks = bx_get('l');
bx_import('BxDolEmbed');
$oEmbed = BxDolEmbed::getObjectInstance('sys_system');
/*

echo bx_file_get_contents('https://publish.twitter.com/oembed?url=https://twitter.com/callmehouck/status/1780211820424569127', [], 'get', ['Accept: text/html'], $iHttpCode, [], 0, [CURLOPT_USERAGENT => false]);*/


//, bx_get('theme')
if (bx_get('html')){
    /* $a  = bx_get_site_info($aLinks, array(
            'thumbnailUrl' => array('tag' => 'link', 'content_attr' => 'href'),
            'OGImage' => array('name_attr' => 'property', 'name' => 'og:image'),
            'icon' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'shortcut icon', 'content_attr' => 'href'),
            'icon2' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'icon', 'content_attr' => 'href'),
            'icon3' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'apple-touch-icon', 'content_attr' => 'href'),
         
        ));
    
    print_r( $a);*/
    echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"" /><style>body, html {margin:0px} A{text-decoration:none; color:#222}</style><div style=" margin:0px auto; justify-content: center;" id="ifr">
    '.$oEmbed->getHtml($aLinks, '');
    exit();
}
echoJson($oEmbed->parseLinks($aLinks));