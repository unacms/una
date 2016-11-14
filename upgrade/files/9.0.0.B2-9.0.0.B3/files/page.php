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

$oTemplate = BxDolTemplate::getInstance();

$oPage = BxDolPage::getObjectInstanceByURI();
if ($oPage) {

    $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
    $oTemplate->setPageContent ('page_main_code', $oPage->getCode());
    $oTemplate->getPageCode();

} else {

    $oTemplate->displayPageNotFound();
}

/** @} */
