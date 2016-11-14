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

header('Content-type: text/html; charset=utf-8');
echo $oMenu->getCode ();

/** @} */
