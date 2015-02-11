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
    ob_start();

    if (bx_get('my_textarea'))
        echo 'Submitted data:<hr class="bx-def-hr" />' . bx_process_output(bx_get('my_textarea')) . '<hr class="bx-def-hr" />';

    echo '<form method="post" id="my_form">'; // form must have id
    echo '<textarea id="my_textarea" name="my_textarea" rows="20" cols="80">some text here</textarea>'; // print text area element
    echo '<input type="submit" value="Submit" class="bx-btn bx-def-margin-sec-top" style="float:none;" />';
    echo '</form>';

    $oEditor = BxDolEditor::getObjectInstance(); // get default editor object instance
    if ($oEditor) // check if editor is available for using
        echo $oEditor->attachEditor ('#my_textarea'); // output HTML which will automatically apply editor to textarea element by its id

    // print all available editors and editors view modes below

    echo '<hr class="bx-def-hr" />';

    $aEditors = array('sys_tinymce');
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

    return DesignBoxContent("Visual editor", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
