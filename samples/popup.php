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
 * @section popup Popups
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
    sleep(2); // to see AJAX loader
    echo date(DATE_RFC822);
    exit;
}

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample popup");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    // transBox    
    echo BxTemplFunctions::getInstance()->transBox('bx-sample-popup-transbox', '<div class="bx-def-padding">transBox</div>', true);
    echo '<div></div><button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-transbox\').dolPopup()">transBox</button>';

    // transBox AJAX
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(window).dolPopupAjax({url: \'samples/popup.php?_t=a'.time().'\'})">transBox AJAX</button>';

    // popupBox
    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-popup-box', 'popupBox', 'popupBox content', true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-box\').dolPopup()">popupBox</button>';

    // popupBox without fog
    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-popup-box-without-fog', 'popupBox', 'popupBox without fog content', true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-box-without-fog\').dolPopup({fog:0})">popupBox without fog</button>';

    echo '<div class="bx-clear"></div>';
    echo '<hr class="bx-def-hr" />';

    // transBox    
    echo BxTemplFunctions::getInstance()->transBox('bx-sample-popup-transbox-scrolled', '<div class="bx-def-padding">' . str_repeat('transBox transBox transBox <br />', 100) . '</div>', true);
    echo '<div></div><button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-transbox-scrolled\').dolPopup()">transBox scrolled</button>';

    // popupBox
    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-popup-box-scrolled', 'popupBox', str_repeat('popupBox ', 1000), true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-box-scrolled\').dolPopup()">popupBox scrolled</button>';

    echo '<div class="bx-clear"></div>';
    echo '<hr class="bx-def-hr" />';

    // transBox with pointer
    echo BxTemplFunctions::getInstance()->transBox('bx-sample-popup-transbox-with-pointer', '<div class="bx-def-padding">transBox with pointer</div>', true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-transbox-with-pointer\').dolPopup({pointer:{el:$(this)}})">transBox with pointer</button>';

    // popupBox with pointer
    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-popup-box-with-pointer', 'popupBox', 'popupBox with pointer content', true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-box-with-pointer\').dolPopup({pointer:{el:$(this)}})">popupBox with pointer</button>';

    // popupBox with pointer AJAX
    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-popup-box-with-pointer', 'popupBox', 'popupBox with pointer AJAX', true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(this).dolPopupAjax({url: \'samples/popup.php?_t=b'.time().'\'})">popupBox with pointer AJAX</button>';

    echo '<div class="bx-clear"></div>';
    echo '<hr class="bx-def-hr" />';

    // image popup
    echo BxTemplFunctions::getInstance()->transBox('bx-sample-popup-image', '<img src="samples/img/landscape.jpg" />', true, true);
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/landscape.jpg\', this)">popupImage (landscape)</button>';
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/landscape_wide.jpg\', this)">popupImage (landscape wide)</button>';
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/portrate.jpg\', this)">popupImage (portrate)</button>';
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/portrate_thin.jpg\', this)">popupImage (portrate thin)</button>';
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/small.jpg\', this)">popupImage (small)</button>';
    echo '<button class="bx-btn bx-def-margin-sec" onclick="$(\'#bx-sample-popup-image\').dolPopupImage(\'samples/img/square.jpg\', this)">popupImage (square)</button>';

    echo str_repeat('&nbsp; <br />', 100);

    return DesignBoxContent("Sample popup", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
