<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

ob_start();
require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");
ob_end_clean();

bx_import('BxDolLanguages');

if (isset($_COOKIE['memberID']) && isset($_COOKIE['memberPassword']))
    bx_logout();

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_TRANSITION);
$oTemplate->setPageHeader (_t('_Please Wait'));
$oTemplate->setPageContent ('page_main_code', MsgBox(_t('_Please Wait')));
$oTemplate->setPageContent ('url_relocate', BX_DOL_URL_ROOT);

send_headers_page_changed();

$oTemplate->getPageCode();

/** @} */
