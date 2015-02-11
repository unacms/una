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
 * @section interface Interface
 */

/**
 * Interface elements
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$GLOBALS['SAMPLE_TEXT'] = 'Lorem ipsum dolor sit amet, <b>vitae tractatos philosophia ius ei</b>, per id causae integre voluptatibus. An iusto <a href="javascript:void(0);">rationibus concludaturque vix</a>, ei dicat admodum minimum vix. Vim ne eruditi scripserit. Nonumy dolorem ex eum, <i>te quo aliquid mnesarchum</i>, ea mel dico populo. Accumsan platonem salutandi te mei, nostro epicurei per ea. Sed ut nonumy sapientem, <u>stet postea periculis eu nec</u>, et purto erat facilisis sed.
';

$GLOBALS['SAMPLE_TITLE'] = 'Lorem ipsum dolor sit amet';

$GLOBALS['SAMPLE_THUMB'] = 'modules/boonex/persons/template/images/no-picture-preview.png';

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Interface elements");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->addCss(BX_DOL_URL_PLUGINS_PUBLIC . 'jush/jush.css');
$oTemplate->addJs(BX_DOL_URL_PLUGINS_PUBLIC . 'jush/jush.js');
$oTemplate->addJs(BX_DOL_URL_ROOT . 'samples/jquery.smint.js');
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();
?>
    <script>
        function samples_interface_toggle_code(e)
        {
            var ePre = $(e).parent().find('pre');
            if (!ePre.hasClass('syntax-highlighed')) {
                var s = ePre.html().replace(/\s+$/gm, '');
                ePre.html(jush.highlight('htm', s).replace(/\t/g, '&nbsp; &nbsp; ').replace(/(^|\n| ) /g, '$1&nbsp;'));
                ePre.addClass('syntax-highlighed');
            }
            ePre.toggle();
        }
    </script>
    <style>
        pre {
            display:none;
            border:1px solid #060;
            padding:5px;
            font-size:14px;
            max-height:200px;
            overflow:auto;
        }
        section hr:first-child {
            display:none;
        }
        section > div {
            box-sizing:border-box;
            -moz-box-sizing:border-box; /* Firefox */
            display:inline-block;
            width:50%;
            vertical-align:top;
        }
        section > div > h3 {
            margin:0;
        }
        .sample-box {
            float:left;;
            width:64px;
            height:64px;
            border:1px solid #966;
            background-color:#c99
        }
        .sample-box-empty {
            height:64px;
        }
    </style>
<?php
    $s = ob_get_clean();

    $sMenu = '<ul class="samples-interface-menu">';
    $aSections = array (
        'Buttons' => array ('title' => 'Buttons', 'desc' => '
            Using buttons with images is strictly discouraged - use font icons istead.'
        ),
        'PaddingsMargins' => array ('title' => 'Paddings & margins', 'desc' => '
            3 sizes are available: default, -sec, -thd. <br />
            Possible combination: -left, -right, -top, -bottom, -leftright, -rightbottomleft, -topbottom. <br />
            -auto is special suffix to be added for -left and -top margin combinations, if it is first then margin is 0. <br />
            -thd is special size to be used as padding and margin together to get proper spacing from borders and between items when several tiles are displayed.'
        ),
        'BordersCornersShadow' => array ('title' => 'Borders, corners and shadow', 'desc' => '
            Possible combination of borders only: -left, -right, -top, -bottom, -rightbottomleft. <br />
            -auto is special suffix to be added for -top border, if element is first then there is no border.'
        ),
        'ThumbsIconsUnits' => array ('title' => 'Thumbs, icons & units', 'desc' => '
            Additianl classes are available: bx-def-thumb-size-max-height, bx-def-thumb-size-min-height, bx-def-icon-size-max-height, bx-def-icon-size-min-height'
        ),
        'Colors' => array ('title' => 'Colors', 'desc' => '
            <a href="samples/palette.php">Additional pallette</a> is also available.'
        ),
        'Fonts' => array ('title' => 'Fonts', 'desc' => ''
        ),
        'Form' => array ('title' => 'Form', 'desc' => '
            <a href="samples/forms.php">Form sample is here</a>'
        ),
        'Grid' => array ('title' => 'Grid', 'desc' => '
            <a href="samples/grid.php">Grid sample is here</a>'
        ),
        'DesignBoxes' => array ('title' => 'Design Boxes', 'desc' => '
            <a href="samples/designbox.php">Design boxes samples are here</a>'
        ),
    );
    foreach ($aSections as $sName => $a) {
        $sMenu  .= '<li><a href="samples/interface.php#' . $sName . '">' . $a['title'] . '</a></li>';
        $s .= '<div id="' . $sName. '" class="bx-anchor bx-def-margin-top-auto">';
        $s .= '<hr class="bx-def-hr" />';
        $s .= '<hr class="bx-def-hr" />';
        $s .= '<h1 class="bx-def-margin-sec-topbottom bx-def-padding-sec-topbottom">' . $a['title'] . '</h1>';
        if ($a['desc'])
            $s .= '<p>' . $a['desc'] . '</p>';
        $sFunc = 'Samples_' . $sName;
        if (function_exists($sFunc))
            $s .= $sFunc();
        $s .= '</div>';
    }
    $sMenu .= '</ul>';

    return DesignBoxContent("Interface elements", $sMenu . $s, BX_DB_PADDING_NO_CAPTION);
}

function Samples_Buttons()
{
    $aBlocks = array ();

    ob_start();
    ?>
<button class="bx-btn bx-def-margin-right">Button</button>
<button class="bx-btn bx-btn-primary bx-def-margin-right">Primary button</button>
<button class="bx-btn bx-btn-disabled bx-def-margin-right">Disabled button</button>
    <?php
    $aBlocks['.bx-btn'] = ob_get_clean();

    ob_start();
    ?>
<button class="bx-btn bx-btn-small bx-def-margin-right">Button</button>
<button class="bx-btn bx-btn-small bx-btn-primary bx-def-margin-right">Primary button</button>
<button class="bx-btn bx-btn-small bx-btn-disabled bx-def-margin-right">Disabled button</button>
    <?php
    $aBlocks['.bx-btn .bx-btn-small'] = ob_get_clean();

    ob_start();
    ?>
<button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i></button>
<button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher sys-icon-bigger"></i></button>
<button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher sys-icon-bigger col-red2"></i></button>
<button class="bx-btn bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i><u>Button</u></button>
    <?php
    $aBlocks['.bx-btn with icon'] = ob_get_clean();

    ob_start();
    ?>
<button class="bx-btn bx-btn-small bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i></button>
<button class="bx-btn bx-btn-small bx-def-margin-right"><i class="sys-icon fire-extinguisher"></i><u>Button</u></button>
    <?php
    $aBlocks['.bx-btn .bx-btn-small with icon'] = ob_get_clean();

    ob_start();
    ?>
<button class="bx-btn bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /></button>
<button class="bx-btn bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png"><u>Button</u></button>
    <?php
    $aBlocks['.bx-btn with image'] = ob_get_clean();

    ob_start();
    ?>
<button class="bx-btn bx-btn-small bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /></button>
<button class="bx-btn bx-btn-small bx-btn-img bx-def-margin-right"><img src="template/images/icons/clock.png" /><u>Button</u></button>
    <?php
    $aBlocks['.bx-btn .bx-btn-small with image'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-btn-group">
    <button class="bx-btn bx-def-margin-right">Button</button>
    <button class="bx-btn bx-def-margin-right">Shmatton</button>
    <button class="bx-btn bx-def-margin-right">Tratton</button>
</div>
    <?php
    $aBlocks['.bx-btn-group'] = ob_get_clean();

    return FormatBlocks($aBlocks);
}

function Samples_PaddingsMargins()
{
    $aBlocks = array ();

    ob_start();
    ?>
<div class="bx-def-border bx-def-padding">
    <?php echo $GLOBALS['SAMPLE_TEXT']; ?>
</div>
    <?php
    $aBlocks['.bx-def-padding'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-def-border bx-def-padding-left bx-def-padding-right">
    <?php echo $GLOBALS['SAMPLE_TEXT']; ?>
</div>
    <?php
    $aBlocks['.bx-def-padding-left .bx-def-padding-right'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-def-border bx-def-padding-sec">
    <?php echo $GLOBALS['SAMPLE_TEXT']; ?>
</div>
    <?php
    $aBlocks['.bx-def-padding-sec'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-clearfix">
    <div class="sample-box bx-def-margin-left"></div>
    <div class="sample-box bx-def-margin-left"></div>
</div>
    <?php
    $aBlocks['.bx-def-margin-left'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-clearfix">
    <div class="sample-box bx-def-margin-left-auto"></div>
    <div class="sample-box bx-def-margin-left-auto"></div>
</div>
    <?php
    $aBlocks['.bx-def-margin-left-auto'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-def-border bx-clearfix">
    <div class="sample-box bx-def-margin-sec"></div>
    <div class="sample-box bx-def-margin-sec"></div>
    <div class="sample-box bx-def-margin-sec"></div>
</div>
    <?php
    $aBlocks['.bx-def-margin-sec'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-def-border bx-clearfix bx-def-padding-thd">
    <div class="sample-box bx-def-margin-thd"></div>
    <div class="sample-box bx-def-margin-thd"></div>
    <div class="sample-box bx-def-margin-thd"></div>
</div>
    <?php
    $aBlocks['.bx-def-padding-thd and .bx-def-margin-thd'] = ob_get_clean();

    return FormatBlocks($aBlocks);
}

function Samples_Colors()
{
    $aBlocks = array ();

    $aClasses = array ('bx-def-color-bg-page', 'bx-def-color-bg-block', 'bx-def-color-bg-box', 'bx-def-color-bg-sec', 'bx-def-color-bg-active', 'bx-def-color-bg-hl', 'bx-def-color-bg-hl-hover');
    foreach ($aClasses as $sClass) {
        ob_start();
        ?>
<div class="bx-def-border sample-box-empty <?php echo $sClass; ?>">
</div>
        <?php
        $aBlocks['.' . implode(" .", explode(' ', $sClass))] = ob_get_clean();
    }

    return FormatBlocks($aBlocks);
}

function Samples_BordersCornersShadow()
{
    $aBlocks = array ();

    $aClasses = array ('bx-def-border', 'bx-def-border-left bx-def-border-bottom', 'bx-def-border bx-def-round-corners', 'bx-def-shadow');
    foreach ($aClasses as $sClass) {
        ob_start();
        ?>
<div class="sample-box-empty <?php echo $sClass; ?>">
</div>
        <?php
        $aBlocks['.' . implode(" .", explode(' ', $sClass))] = ob_get_clean();
    }

    return FormatBlocks($aBlocks);
}

function Samples_ThumbsIconsUnits()
{
    $aBlocks = array ();

    $aClasses = array ('bx-def-thumb bx-def-thumb-size', 'bx-def-icon bx-def-icon-size');
    foreach ($aClasses as $sClass) {
        ob_start();
        ?>
<img class="<?php echo $sClass; ?>" src="<?php echo $GLOBALS['SAMPLE_THUMB']; ?>" />
        <?php
        $aBlocks['.' . implode(" .", explode(' ', $sClass))] = ob_get_clean();
    }

    ob_start();
    ?>
<div class="bx-def-unit">
    <a href="javascript:void(0);"><img class="bx-def-thumb bx-def-thumb-size" src="<?php echo $GLOBALS['SAMPLE_THUMB']; ?>" /></a>
    <div class="bx-def-unit-info bx-def-thumb-size-max-height bx-def-padding-left">
        <span class="bx-def-font-large">Some info here</span><br />
        <span class="bx-def-font-grayed">additional info</span>
    </div>
</div>
    <?php
    $aBlocks['.bx-def-unit .bx-def-thumb'] = ob_get_clean();

    ob_start();
    ?>
<div class="bx-def-unit">
    <a href="javascript:void(0);"><img class="bx-def-icon bx-def-icon-size" src="<?php echo $GLOBALS['SAMPLE_THUMB']; ?>" /></a>
    <div class="bx-def-unit-info bx-def-icon-size-max-height bx-def-padding-sec-left">
        Some info here
    </div>
</div>
    <?php
    $aBlocks['.bx-def-unit .bx-def-icon'] = ob_get_clean();

    return FormatBlocks($aBlocks);
}

function Samples_Fonts()
{
    $aBlocks = array ();

    $aClasses = array ('' => 'SAMPLE_TEXT', 'bx-def-font-grayed' => 'SAMPLE_TEXT', 'bx-def-font-small' => 'SAMPLE_TEXT', 'bx-def-font-middle' => 'SAMPLE_TEXT', 'bx-def-font-large' => 'SAMPLE_TEXT', 'bx-def-font-h1' => 'SAMPLE_TITLE', 'bx-def-font-h2' => 'SAMPLE_TITLE', 'bx-def-font-h3' => 'SAMPLE_TITLE', 'bx-def-font-contrasted bx-def-font-h2 bg-col-green3' => 'SAMPLE_TITLE');
    foreach ($aClasses as $sClass => $sSampleText) {
        ob_start();
        ?>
<div class="<?php echo $sClass; ?>">
<?php echo $GLOBALS[$sSampleText]; ?>
</div>
        <?php
        $aBlocks['.' . implode(" .", explode(' ', $sClass))] = ob_get_clean();
    }

    return FormatBlocks($aBlocks);
}

function FormatBlocks ($aBlocks)
{
    $s = '<section class="bx-def-padding-top">';
    foreach ($aBlocks as $sCaption => $sBlock) {
        $s .= '<hr class="bx-def-hr bx-def-margin-topbottom" />';
        $s .= '<div>' . $sBlock . '</div>';
        $s .= '<div class="bx-def-padding-left"><h3>' . $sCaption . '</h3><a href="javascript:void(0);" onclick="samples_interface_toggle_code(this)">Toggle code</a><br /><pre>' . $sBlock . '</pre></div>';
    }
    $s .= '</section>';
    return $s;
}

/** @} */
