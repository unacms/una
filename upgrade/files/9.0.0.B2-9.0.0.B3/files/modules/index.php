<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once("./../inc/header.inc.php");

$GLOBALS['aRequest'] = explode('/', $_GET['r']);

$sName = bx_process_input(array_shift($GLOBALS['aRequest']));

bx_import('BxDolModuleQuery');
$GLOBALS['aModule'] = BxDolModuleQuery::getInstance()->getModuleByUri($sName);

if (empty($GLOBALS['aModule'])) {
    require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
    BxDolRequest::moduleNotFound($sName);
}

include(BX_DIRECTORY_PATH_MODULES . $GLOBALS['aModule']['path'] . 'request.php');

/** @} */
