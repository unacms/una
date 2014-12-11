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
 * @section image_resize Image Resize
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxTemplFunctions');
bx_import('BxDolImageResize');

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ('Image Resize');
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    $sFile = '';
    $sImgTmpDir = BX_DIRECTORY_PATH_TMP . 'img_resize_';
    $sImgSrcDir = BX_DIRECTORY_PATH_ROOT . 'samples/img/landscape.jpg';

    $o = BxDolImageResize::getInstance();
    $o->removeCropOptions ();
    $o->setJpegOutput(true);

    switch (bx_get('action')) {
        case 'img_src':
            $sFile = $sImgSrcDir;
            break;
        case 'img_resize':
            $sFile = $sImgTmpDir . 'resize.jpg';
            $o->setSize (200, 150);
            $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_autocrop':
            $sFile = $sImgTmpDir . 'resize_autocrop.jpg';
            $o->setSize (200, 150);
            $o->setAutoCrop (true);
            $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_square':
            $sFile = $sImgTmpDir . 'resize_square.jpg';
            $o->setSize (150, 150);
            $o->setSquareResize (true);
            $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_grayscale':
            $sFile = $sImgTmpDir . 'grayscale.jpg';
            $o->grayscale($sImgSrcDir, $sFile);
            break;
    }

    if ($sFile) {
        header("Content-Type: image/jpeg");
        readfile($sFile);
        exit;
    }

    ob_start();

?>
    Source:<br /> <img border=1 style="max-width:300px;" src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_src" />
    <hr class="bx-def-hr" />
    Resized:<br /> <img border=1 src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_resize" />
    <hr class="bx-def-hr" />
    Autocrop resized:<br /> <img border=1 src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_resize_autocrop" />
    <hr class="bx-def-hr" />
    Square resized:<br /> <img border=1 src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_resize_square" />
    <hr class="bx-def-hr" />
    Grayscaled:<br /> <img border=1 style="max-width:300px;" src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_grayscale" />
<?php

    return DesignBoxContent("Image Resize", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
