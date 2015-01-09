<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");

bx_import('BxDolLanguages');

$sObject = bx_process_input(bx_get('o'));
if (!$sObject)
    exit;

bx_import('BxDolGrid');
$oGrid = BxDolGrid::getObjectInstance($sObject);
if (!$oGrid) {
    // no such grid object available
    exit;
}

$sAction = 'performAction' . bx_gen_method_name(bx_process_input(bx_get('a')));
if (method_exists($oGrid, $sAction)) {
    $oGrid->$sAction();
}

/** @} */
