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
 * @section palette Palette
 */

/**
 * This sample shows default palette colors.
 * Palette colors are defined as css classes.
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Palette");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $aColors = array ('red1', 'red1-dark', 'red2', 'red2-dark', 'red3', 'red3-dark', 'green1', 'green1-dark', 'green2', 'green2-dark', 'green3', 'green3-dark', 'blue1', 'blue1-dark', 'blue2', 'blue2-dark', 'blue3', 'blue3-dark', 'gray', 'gray-dark');

    $aDefBg = array ('bx-def-color-bg-page', 'bx-def-color-bg-block', 'bx-def-color-bg-box', 'bx-def-color-bg-sec', 'bx-def-color-bg-active', 'bx-def-color-bg-hl', 'bx-def-color-bg-hl-hover');

    $sStyle = 'width:300px; height:100px; line-height:100px; display:inline-block; font-size:20px; font-weight:bold; text-align:center;';

    foreach ($aColors as $sColor) {
        $sClass = 'bx-def-round-corners bx-def-margin-right bx-def-margin-bottom';
        $sClassFt = 'col-'.$sColor;
        $sClassBg = 'bg-col-'.$sColor;
?>
        <div>
            <div class="<?php echo $sClass;?> <?php echo $sClassBg;?>" style="<?php echo $sStyle;?> color:#fff;">.<?php echo $sClassBg;?></div>
            <div class="<?php echo $sClass;?> <?php echo $sClassFt;?>" style="<?php echo $sStyle;?> border:1px solid #ccc;">.<?php echo $sClassFt;?></div>
        </div>
<?php
    }

?>
        <hr class="bx-def-hr bx-def-margin-top bx-def-margin-bottom" />
<?php

    foreach ($aDefBg as $sClassBg) {
        $sClass = 'bx-def-round-corners bx-def-margin-right bx-def-margin-bottom';
?>
        <div>
            <div class="<?php echo $sClass;?> <?php echo $sClassBg;?> bx-def-border" style="<?php echo $sStyle;?>">.<?php echo $sClassBg;?></div>
        </div>
<?php
    }

    $s = ob_get_clean();
    return DesignBoxContent("Palette", $s, BX_DB_PADDING_DEF);
}

/** @} */
