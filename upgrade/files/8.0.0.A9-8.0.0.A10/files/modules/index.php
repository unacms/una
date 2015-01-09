<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
