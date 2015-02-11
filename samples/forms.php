<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Sample form");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $aForm = array(
        'form_attrs' => array(
            'id' => 'sample_form',
            'action' => BX_DOL_URL_ROOT . 'samples/forms.php',
            'method' => 'post',
        ),
        'params' => array (
            'db' => array(
                'submit_name' => 'do_submit',
            ),
        ),
        'inputs' => array(

            'header_contact' => array(
                'type' => 'block_header',
                'caption' => 'Contact details',
            ),

            'name' => array(
                'type' => 'text',
                'name' => 'name',
                'caption' => _t('_Your name'),
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(1, 150),
                    'error' => _t( '_Name is required' )
                ),
            ),
            'date' => array(
                'type' => 'datepicker',
                'name' => 'date',
                'caption' => _t('Date'),
                'required' => true,
                'checker' => array(
                    'func' => 'avail',
                    'error' => _t( 'Date is required' )
                ),
            ),
            'datetime' => array(
                'type' => 'datetime',
                'name' => 'datetime',
                'caption' => _t('Datetime'),
                'required' => true,
                'checker' => array(
                    'func' => 'avail',
                    'error' => _t( 'Datetime is required' )
                ),
            ),
            'email' => array(
                'type' => 'text',
                'name' => 'email',
                'caption' => _t('_Your email'),
                'required' => true,
                'checker' => array(
                    'func' => 'email',
                    'error' => _t( '_Incorrect Email' )
                ),
            ),

            'header_message' => array(
                'type' => 'block_header',
                'caption' => 'Message details',
                'collapsable' => true,
                'collapsed' => false,
            ),

            'message_subject' => array(
                'type' => 'text',
                'name' => 'subject',
                'caption' => _t('_message_subject'),
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(5, 300),
                    'error' => _t( '_ps_ferr_incorrect_length' )
                ),
            ),
            'message_text' => array(
                'type' => 'textarea',
                'name' => 'body',
                'caption' => _t('_Message text'),
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(10, 5000),
                    'error' => _t( '_ps_ferr_incorrect_length' )
                ),
            ),

            'header_advanced' => array(
                'type' => 'block_header',
                'caption' => 'Advanced details',
                'collapsable' => true,
                'collapsed' => true,
            ),

            'select_something' => array(
                'type' => 'select',
                'name' => 'select_something',
                'caption' => _t('Select something'),
                'values' => array (1 => 'One', 'Two', 'Three', 'Four', 'Five'),
                'required' => true,
            ),

            'select_radio' => array(
                'type' => 'radio_set',
                'name' => 'select_radio',
                'caption' => _t('Select radio something'),
                'values' => array (1 => 'One', 'Two', 'Three'),
                'required' => true,
            ),

            'choose_something' => array(
                'type' => 'checkbox_set',
                'name' => 'choose_something',
                'caption' => _t('Choose something'),
                'values' => array (1 => 'One', 'Two', 'Three', 'Four', 'Five'),
                'required' => true,
            ),

            'select_multiple' => array(
                'type' => 'select_multiple',
                'name' => 'select_multiple',
                'caption' => _t('Multiple select'),
                'values' => array (1 => 'One', 'Two', 'Three', 'Four', 'Five'),
                'required' => true,
            ),

            'doublerange' => array(
                'type' => 'doublerange',
                'name' => 'doublerange',
                'caption' => _t('Double Range'),
                'attrs' => array('min' => 10, 'max' => 200),
                'value' => '20-30',
                'required' => true,
            ),

            'header_submit' => array(
                'type' => 'block_header',
                'caption' => '',
            ),

            'iagree' => array(
                'type' => 'checkbox',
                'name' => 'iagree',
                'value' => '1',
                'caption' => _t('Do you like it?'),
                'required' => true,
                'checker' => array(
                    'func' => 'avail',
                    'error' => _t( 'Form can not be submitted if you don\'t like it.' )
                ),
            ),

            'submit' => array(
                'type' => 'input_set',
                0 => array (
                    'type' => 'submit',
                    'name' => 'do_submit',
                    'value' => _t('_Submit'),
                ),
                1 => array (
                    'type' => 'reset',
                    'name' => 'close',
                    'value' => _t('_Reset'),
                    'attrs' => array('class' => 'bx-def-margin-sec-left'),
                ),
            ),

        ),
    );

    $oForm = new BxTemplFormView($aForm);
    $oForm->initChecker();
    if ( $oForm->isSubmittedAndValid() ) {
        echo  MsgBox('Data was successfully submitted');
    }

    echo $oForm->getCode();

    $s = ob_get_clean();
    return DesignBoxContent("Sample form", $s, BX_DB_PADDING_DEF);

}

/** @} */
