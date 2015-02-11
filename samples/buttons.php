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
 * @section button Buttons
 */

/**
 * There are default css classes for buttons.
 *
 * .bx-btn - standard button
 * .bx-btn .bx-btn-small - small buttom
 *
 * To make button with font icon, add icon before text and wrap text in 'u' tag:
 * @code
 *   <i class="sys-icon fire-extinguisher"></i><u>Button text</u>
 * @endcode
 *
 * To make button with image, add 'img' tag before text and wrap text in 'u' tag:
 * @code
 *   <img src="clock.png" /><u>Button text</u>
 * @endcode
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");


$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Buttons");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();
?>
    <button class="bx-btn bx-def-margin-right">Button text</button>
    <button class="bx-btn bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /></button>
    <button class="bx-btn bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png"><u>Button text</u></button>

    <div class="bx-clear"></div><hr class="bx-def-hr bx-def-margin-topbottom" />

    <button class="bx-btn bx-btn-small bx-def-margin-right">Button text</button>
    <button class="bx-btn bx-btn-small bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /></button>
    <button class="bx-btn bx-btn-small bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /><u>Button text</u></button>

    <div class="bx-clear"></div><hr class="bx-def-hr bx-def-margin-topbottom" />

    <button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i></button>
    <button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher sys-icon-bigger"></i></button>
    <button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher sys-icon-bigger col-red1"></i></button>
    <button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i><u>Button text</u></button>

    <div class="bx-clear"></div><hr class="bx-def-hr bx-def-margin-topbottom" />

    <button class="bx-btn bx-btn-small bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i></button>
    <button class="bx-btn bx-btn-small bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i><u>Button text</u></button>

<?php

    return DesignBoxContent("Buttons", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
