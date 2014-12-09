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

bx_import('BxDolLanguages');
bx_import('BxDolTemplate');
bx_import('BxDolPage');

check_logged();

$oTemplate = BxDolTemplate::getInstance();

$sURI = bx_process_input(bx_get('i'));
$oPage = BxDolPage::getObjectInstanceByURI($sURI);

if ($oPage) {

    $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
    $oTemplate->setPageContent ('page_main_code', $oPage->getCode());
    $oTemplate->getPageCode();

} else {

    $oTemplate->displayPageNotFound();
}

/** @} */
