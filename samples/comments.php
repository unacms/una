<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section comments Comments
 */

/**
 * Please refer to the following file for custom class and SQL dump data for this example:
 * @see BxCmtsMy.php
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ('Comments');
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    $iObjectId = 1;

    bx_import('BxDolCmts');
    $oCmts = BxDolCmts::getObjectInstance('sample', $iObjectId);
    if(!$oCmts->isEnabled())
        return '';

    return $oCmts->getCommentsBlock();
}

/** @} */
