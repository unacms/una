<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
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
