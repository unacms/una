<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentEndAdmin Trident Studio End Admin Pages
 * @ingroup     TridentStudio
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

bx_import('BxTemplStudioWidgets');
$oPage = new BxTemplStudioWidgets('builders');

bx_import('BxDolStudioTemplate');
$oTemplate = BxDolStudioTemplate::getInstance();
$oTemplate->setPageNameIndex($oPage->getPageIndex());
$oTemplate->setPageHeader($oPage->getPageHeader());
$oTemplate->setPageContent('page_main_code', $oPage->getPageCode());
$oTemplate->getPageCode();
/** @} */
