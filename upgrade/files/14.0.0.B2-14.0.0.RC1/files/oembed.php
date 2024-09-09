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

if (getParam('sys_embed_default') != 'sys_oembed')
    exit;

$aLinks = bx_get('l');

bx_import('BxDolEmbed');
if(($oEmbed = BxDolEmbed::getObjectInstance('sys_system')) !== false) {
    if(bx_get('html'))
        echo '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"" /><style>body, html {margin:0px} A{text-decoration:none; color:#222}</style><div style=" margin:0px auto; justify-content: center;" id="ifr">' . $oEmbed->getLinkHTML($aLinks) . '</div>';
    else
        echoJson($oEmbed->parseLinks($aLinks));
}
