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

bx_import('BxDolLanguages');

check_logged();

if(($sObject = bx_get('o')) !== false && ($sAction = bx_get('a')) !== false) {
    $sObject = bx_process_input($sObject);
    $sAction = bx_process_input($sAction);
    if(!empty($sObject) && !empty($sAction)) {
        $oPage = BxDolPage::getObjectInstance($sObject);
        $sAction = 'performAction' . bx_gen_method_name($sAction);
        if($oPage && method_exists($oPage, $sAction)) {
            $oPage->$sAction();
            exit;
        }
    }
}

BxDolPage::seoRedirect();

$oPage = BxDolPage::getObjectInstanceByURI('', false, true);
if ($oPage) {
    $oPage->displayPage();

} else {
    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->displayPageNotFound();
}

/** @} */
