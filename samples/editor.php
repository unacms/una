<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreSamples Samples
 * @{
 */

/**
 * @page samples
 * @section editor Visual editor
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Visual editor");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    $oEditor = BxDolEditor::getObjectInstance(); // get default editor object instance
    
    ob_start();

    if ('editor' == bx_get('action')) {
        echo '<textarea id="my_textarea_popup" name="my_textarea_popup" rows="20" cols="80">Hello World!</textarea>';
        echo $oEditor->attachEditor('#my_textarea_popup', BX_EDITOR_STANDARD, true);
        exit;
    }
    elseif (bx_get('my_textarea')) {
        echo 'Submitted data:<hr class="bx-def-hr" />' . bx_process_output(bx_get('my_textarea')) . '<hr class="bx-def-hr" />';
    }

    // standard editor

    echo '<form method="post" id="my_form">'; // form must have id
    echo '<textarea id="my_textarea" name="my_textarea" rows="20" cols="80">some text here</textarea>'; // print text area element
    echo '<input type="submit" value="Submit" class="bx-btn bx-def-margin-sec-top" style="float:none;" />';
    echo '</form><hr class="bx-def-hr" />';


    // standard editor in popup

    echo BxTemplFunctions::getInstance()->popupBox('bx-sample-editor-in-popup', 'popupBox', 'Editor in popup', true);
    echo '<button class="bx-btn" onclick="$(window).dolPopupAjax({url: \'' . BX_DOL_URL_ROOT . 'samples/editor.php?action=editor&_t=b'.time().'\', removeOnClose: true})">Editor in popup</button>';

    
    if ($oEditor) // check if editor is available for using
        echo $oEditor->attachEditor ('#my_textarea'); // output HTML which will automatically apply editor to textarea element by its id

    // print all available editors and editors view modes below
/*
    echo '<hr class="bx-def-hr" />';

    $aEditors = array('sys_quill', 'bx_froala');
    $aViewModes = array(BX_EDITOR_MINI => 'Mini', BX_EDITOR_STANDARD => 'Standard', BX_EDITOR_FULL => 'Full');
    foreach ($aViewModes as $iViewMode => $sViewModeHeader) {
        echo "<h1>$sViewModeHeader</h1>";
        foreach ($aEditors as $sEditor) {
            $sId = 'textarea_' . $sEditor . '_' . $iViewMode;
            echo '<textarea id="' . $sId . '" name="' . $sId . '" rows="20" cols="80">some text here</textarea>';
            $oEditor = BxDolEditor::getObjectInstance($sEditor);
            if (!$oEditor)
                continue;
            echo $oEditor->attachEditor ('#' . $sId, $iViewMode);
            echo '<hr class="bx-def-hr" />';
        }
    }
 */
    return DesignBoxContent("Visual editor", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
