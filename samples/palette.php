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
 * @section palette Palette
 */

/**
 * This sample shows default Dolphin palette colors.
 * Palette colors are defined as css classes.
 */ 

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxDolTemplate');

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Palette");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode() {
    ob_start();
    
    $aColors = array ('red1', 'red1-dark', 'red2', 'red2-dark', 'red3', 'red3-dark', 'green1', 'green1-dark', 'green2', 'green2-dark', 'green3', 'green3-dark', 'blue1', 'blue1-dark', 'blue2', 'blue2-dark', 'blue3', 'blue3-dark', 'gray', 'gray-dark');

    foreach ($aColors as $sColor) {
        $sClass = 'bx-def-round-corners bx-def-margin-right bx-def-margin-bottom';
        $sClassFt = 'col-'.$sColor;
        $sClassBg = 'bg-col-'.$sColor;        
        $sStyle = 'width:300px; height:100px; line-height:100px; display:inline-block; font-size:20px; font-weight:bold; text-align:center;';
?>
    <div>
        <div class="<?=$sClass;?> <?=$sClassBg;?>" style="<?=$sStyle;?> color:#fff;">.<?=$sClassBg;?></div>
        <div class="<?=$sClass;?> <?=$sClassFt;?>" style="<?=$sStyle;?> border:1px solid #ccc;">.<?=$sClassFt;?></div>
    </div>
<?php
    }

    $s = ob_get_clean();
    return DesignBoxContent("Palette", $s, BX_DB_PADDING_DEF);
}

/** @} */
