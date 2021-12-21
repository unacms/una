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
echoJson($oEmbed->parseLinks($aLinks));