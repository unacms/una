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
 * @section form Form
 */

/**
 * Please refer to the following file for custom class and SQL dump data for this example:
 * @see BxSampleForm.php
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample Form Object");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    // get database object
    $oDb = BxDolDb::getInstance();

    // get id of edited data
    $iEditId = bx_process_input(bx_get('id'), BX_DATA_INT);

    // display existing data

    ob_start();

    echo '<a href="' . BX_DOL_URL_ROOT . 'samples/form_objects.php">Add new</a> or edit:<hr class="bx-def-hr" />';

    $a = $oDb->getAllWithKey("SELECT * FROM `sample_input_types`", 'id');
    foreach ($a as $r) {
        if ($iEditId == $r['id'])
            echo $r['text'] . '<br />'; // currently edited data
        else
            echo '<a href="' . BX_DOL_URL_ROOT . 'samples/form_objects.php?id=' . $r['id'] . '">' . $r['text'] . '</a><br />'; // data with link to edit it
    }

    $ss = ob_get_clean();
    ob_start();

    if ($iEditId) {

        // display edit form

        $oForm = BxDolForm::getObjectInstance('sample_form_objects', 'sample_form_objects_edit'); // get form instance for specified form object and display
        if (!$oForm)
            die('"sample_form_objects_edit" form object or "sample_form_objects_edit" display is not defined');
        $oForm->initChecker($a[$iEditId]); // init form checker with edited data
        if ($oForm->isSubmittedAndValid()) {
            if ($oForm->update ($iEditId)) // update database
                echo MsgBox('Data has been updated');
            else
                echo MsgBox('Data update failed');
        } else {
            echo $oForm->getCode(); // display form
        }

    } else {

        // display add form

        $oForm = BxDolForm::getObjectInstance('sample_form_objects', 'sample_form_objects_add'); // get form instance for specified form object and display
        if (!$oForm)
            die('"sample_form_objects_add" form object or "sample_form_objects_add" display is not defined');
        $oForm->initChecker(); // init form checker witout any data - adding new record
        if ($oForm->isSubmittedAndValid()) {
            if ($oForm->insert ()) // add new record to the database
                echo MsgBox('Data has been added');
            else
                echo MsgBox('Data add failed');
        } else {
            echo $oForm->getCode(); // display form
        }

    }

    $s = ob_get_clean();
    return DesignBoxContent("Sample Form Object", $ss, BX_DB_PADDING_DEF) . DesignBoxContent($iEditId ? "Edit" : "Add New", $s, BX_DB_PADDING_DEF);
}

/** @} */
