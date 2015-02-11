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
    $sImgSrcName = 'landscape.jpg';
    $sImgSrcDir = BX_DIRECTORY_PATH_ROOT . 'samples/img/' . $sImgSrcName;

    $o = BxDolImageResize::getInstance();
    $o->removeCropOptions ();

    switch (bx_get('action')) {
        case 'img_src':
            $sFile = $sImgSrcDir;
            break;
        case 'img_resize':
            $sFile = $sImgTmpDir . 'resize_' . $sImgSrcName;
            $o->setSize (200, 150);
            $res = $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_autocrop':
            $sFile = $sImgTmpDir . 'resize_autocrop_' . $sImgSrcName;
            $o->setSize (200, 150);
            $o->setAutoCrop (true);
            $res = $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_square':
            $sFile = $sImgTmpDir . 'resize_square_' . $sImgSrcName;
            $o->setSize (150, 150);
            $o->setSquareResize (true);
            $res = $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_w':
            $sFile = $sImgTmpDir . 'resize_w_' . $sImgSrcName;
            $o->setSize (150, null);
            $res = $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_resize_h':
            $sFile = $sImgTmpDir . 'resize_h_' . $sImgSrcName;
            $o->setSize (null, 150);
            $res = $o->resize($sImgSrcDir, $sFile);
            break;
        case 'img_grayscale':
            $sFile = $sImgTmpDir . 'grayscale_' . $sImgSrcName;
            $res = $o->grayscale($sImgSrcDir, $sFile);
            break;
        case 'img_watermark':
            $sFile = $sImgTmpDir . 'watermark_' . $sImgSrcName;
            $res = $o->applyWatermark($sImgSrcDir, $sFile, BX_DIRECTORY_PATH_ROOT . 'samples/img/ussr.gif', 50, 'bottom-right', 0, 0, 0.2);
            break;
    }

    if ($sFile) {        
        if (isset($res) && IMAGE_ERROR_SUCCESS !== $res) {
            echo $o->getError();
            exit;
        }
        else {
            header("Content-Type: image/jpeg");
            readfile($sFile);
        }
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
    Resized by width:<br /> <img border=1 src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_resize_w" />
    <hr class="bx-def-hr" />
    Resized by height:<br /> <img border=1 src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_resize_h" />
    <hr class="bx-def-hr" />
    Grayscaled:<br /> <img border=1 style="max-width:300px;" src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_grayscale" />
    <hr class="bx-def-hr" />
    Watermark:<br /> <img border=1 style="max-width:300px;" src ="<?php echo BX_DOL_URL_ROOT; ?>samples/image_resize.php?action=img_watermark" />
    <hr class="bx-def-hr" />
    Average color:<br />        
<?php
    $a = $o->getAverageColor($sImgSrcDir);
    echo '<pre>' . print_r($a, 1) . '</pre>';
?>
    <hr class="bx-def-hr" />
    Is image:<br />
<?php
    echo "[" . $o->isAllowedImage ($sImgSrcDir) . "]";

    return DesignBoxContent("Image Resize", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
