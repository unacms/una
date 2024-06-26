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
$oEmbed = BxDolEmbed::getObjectInstance('sys_oembed');

if (bx_get('html')){
    echo '<body style="margin:0"><style>iframe{width:100%} .embera-embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    padding: 0;
    overflow: hidden;
    padding-bottom: 50%;
}
.embera-embed-responsive-item {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.bx-base-attach-link-form-field .embera-embed-responsive {
    padding-bottom: 0;
}
.bx-base-attach-link-form-field .embera-embed-responsive-item {
    position: relative;
    width: auto;
    height: auto;
}</style><div style="max-width:900; margin:0px auto">' . $oEmbed->parseLinkHtml($aLinks) .'</div></body>';
    exit();
}

echoJson($oEmbed->parseLinks($aLinks));