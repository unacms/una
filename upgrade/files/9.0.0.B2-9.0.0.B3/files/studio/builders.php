<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaEndAdmin UNA Studio End Admin Pages
 * @ingroup     UnaStudio
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

bx_import('BxDolLanguages');

/**
 * Is used if AJAX based Launcher is disabled.
 * It displays Widget based Builders page.
 */

bx_require_authentication(true);

$oPage = new BxTemplStudioWidgets('builders');

$oTemplate = BxDolStudioTemplate::getInstance();
$oTemplate->setPageNameIndex($oPage->getPageIndex());
$oTemplate->setPageHeader($oPage->getPageHeader());
$oTemplate->setPageContent('page_main_code', $oPage->getPageCode());
$oTemplate->getPageCode();
/** @} */
