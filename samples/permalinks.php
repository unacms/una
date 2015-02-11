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
 * @section permalinks Permalinks
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Permalinks");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $oPermalinks = BxDolPermalinks::getInstance();

    $a = array (
        'http://192.168.1.215/d8/page/create-account',
        'http://192.168.1.215/d8/page.php?i=create-account',
        'http://192.168.1.215/d8/page.php?o=create-account',
        '/d8/page/create-account',
        '/d8/page.php?i=create-account',
        '/d8/page.php?o=create-account',
        'page/create-account',
        'page.php?i=create-account',
        'page.php?o=create-account',
    );

    foreach ($a as $sLink) {
        echo '<hr /><b>Original:</b> ' . $sLink . '<br />';
        echo '<b>UNpermalinked:</b> ' . ($sLink = $oPermalinks->unpermalink($sLink)) . '<br />';
        echo '<b>Page Name:</b> ' . ($oPermalinks->getPageNameFromLink($sLink)) . '<br />';
        echo '<b>Permalinked:</b> ' . ($sLink = $oPermalinks->permalink($sLink)) . '<br />';
    }

    return DesignBoxContent("Permalinks", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
