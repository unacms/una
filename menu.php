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
require_once(BX_DIRECTORY_PATH_INC . "utils.inc.php");

bx_import('BxDolLanguages');

$sObject = bx_process_input(bx_get('o'));
if (!$sObject)
    exit;

$oMenu = BxDolMenu::getObjectInstance($sObject);
if (!$oMenu)
    exit;

$sAction = 'performAction' . bx_gen_method_name(bx_process_input(bx_get('a')));
if (method_exists($oMenu, $sAction)) {
    $aParams = [];
    if(($mixedValue = bx_get('i')) !== false)
        $aParams[] = bx_process_input(bx_get('i')); //--- item name
    if(($mixedValue = bx_get('v')) !== false)
        $aParams[] = bx_process_input(bx_get('v')); //--- some value

    call_user_func_array([$oMenu, $sAction], $aParams);
    exit;
}

header('Content-type: text/html; charset=utf-8');
echo $oMenu->getCode ();

/** @} */
