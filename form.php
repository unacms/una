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
$sDisplay = bx_process_input(bx_get('d'));
$sAction = bx_process_input(bx_get('a'));
if(!empty($sAction))
    $sAction = 'performAction' . bx_gen_method_name($sAction);

// try to create form object and call its method
if(!empty($sObject) && !empty($sDisplay) && !empty($sAction)) {
    $oForm = BxTemplFormView::getObjectInstance($sObject, $sDisplay);
    if($oForm && method_exists($oForm, $sAction))
        $oForm->$sAction();
}

/** @} */
