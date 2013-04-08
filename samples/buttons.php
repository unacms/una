<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Samples
 * @{
 */

/** 
 * @page samples
 * @section cut_strings Cut strings
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxTemplFunctions');

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Buttons");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();


/**
 * page code function
 */
function PageCompMainCode() {


    echo '<button class="bx-btn">Button 1</button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';

    echo '<button class="bx-btn bx-btn-img"><i style="background-image:url(template/images/icons/clock.png)"></i></button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';

    echo '<button class="bx-btn bx-btn-img"><u style="background-image:url(template/images/icons/clock.png)">Button 1</u></button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';


    echo '<button class="bx-btn bx-btn-small">Button 1</button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';

    echo '<button class="bx-btn bx-btn-small bx-btn-img"><i style="background-image:url(template/images/icons/clock.png)"></i></button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';

    echo '<button class="bx-btn bx-btn-small bx-btn-img"><u style="background-image:url(template/images/icons/clock.png)">Button 1</u></button>';
    echo '<div class="bx-clear"></div><hr class="bx-def-hr" />';

    return DesignBoxContent("Buttons", ob_get_clean(), BX_DB_PADDING_DEF);
}



/** @} */
