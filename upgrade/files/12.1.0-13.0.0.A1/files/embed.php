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
bx_import('BxDolEmbed');

if (bx_get('a') == 'get_link'){
    $sLink = trim(bx_get('l'));

    $oEmbed = BxDolEmbed::getObjectInstance();
    if ($oEmbed)
        echoJson(['code' => $oEmbed->getLinkHTML($sLink), 'js' => strip_tags($oEmbed->addProcessLinkMethod()), 'link' => $sLink]);
}