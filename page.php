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

$oPage = BxDolPage::getObjectInstanceByURI('', false, true);
if ($oPage) {

    $oPage->displayPage();

} else {

    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->displayPageNotFound();
}

/** @} */
