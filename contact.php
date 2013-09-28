<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

// TODO: move to separate module

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxDolMenu');
bx_import('BxDolTemplate');

BxDolMenu::setSelectedGlobal ('system', 'contact'); 

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t("_CONTACT_H"));
$oTemplate->setPageContent ('page_main_code', PageCompPageMainCodeWithForm());
$oTemplate->getPageCode();

/**
 * page code function with contact form
 */
function PageCompPageMainCodeWithForm() {

    $sActionText = '';

    $aFormNested = array(

        'inputs' => array(
            'file_name' => array(
                'type' => 'text',
                'name' => 'file_name[]',
                'value' => '{file_title}',
                'caption' => _t('Caption'),
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(1, 150),
                    'error' => _t( 'Caption name is required' )
                ),
            ),

            'file_desc' => array(
                'type' => 'textarea',
                'name' => 'file_desc[]',
                'caption' => _t('Description'),
                'required' => true,
                'checker' => array(
                    'func' => 'length',
                    'params' => array(10, 5000),
                    'error' => _t( '_ps_ferr_incorrect_length' )
                ),
            ),

        ),
    );

    bx_import('BxDolFormNested');
    $oFormNested = new BxDolFormNested('attachment', $aFormNested, 'do_submit');

    $aForm = array(
        'form_attrs' => array(
            'id' => 'post_us_form',
            'action' => BX_DOL_URL_ROOT . 'contact.php',
            'method' => 'post',
        ),
        'params' => array (
            'db' => array(
                'submit_name' => 'do_submit',
            ),
        ),
        'inputs' => array(
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
            
            'attachment' => array(
                'type' => 'files',
                'storage_object' => 'sample2',
                'uploaders' => array ('sys_simple', 'sys_html5'),
                'images_transcoder' => 'sample2',
                'multiple' => true,
                'content_id' => 0,
                'ghost_template' =>  
                        $oFormNested,
//                        $aFormNested,
/*
                    '<div id="bx-uploader-file-{file_id}" class="bx-uploader-ghost">
                        <div style="border:2px dotted green; padding:10px; margin-bottom:10px;">
                            <input type="hidden" name="f[]" value="{file_id}" />
                            {file_name} <br />
                            <a href="javascript:void(0);" onclick="{js_instance_name}.deleteGhost(\'{file_id}\')">delete</a> 
                        </div>
                    </div>',
*/
                'name' => 'attachment',
                'caption' => _t('Attachments'),
//                'required' => true, // TODO:
            ),
/*
            'captcha' => array(
                'type' => 'captcha',
                'caption' => _t('_Enter what you see:'),
                'name' => 'securityImageValue',
                'required' => true,
                'checker' => array(
                    'func' => 'captcha',
                    'error' => _t( '_Incorrect Captcha' ),
                ),
            ),
*/
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

    bx_import('BxTemplFormView');
    $oForm = new BxTemplFormView($aForm);
    $oForm->initChecker();
    if ( $oForm->isSubmittedAndValid() ) {
        $sSenderName    = bx_process_output(bx_process_input($_POST['name'], BX_DATA_TEXT_MULTILINE), BX_DATA_TEXT_MULTILINE);
        $sSenderEmail   = bx_process_output(bx_process_input($_POST['email'], BX_DATA_TEXT_MULTILINE), BX_DATA_TEXT_MULTILINE);
        $sLetterSubject = bx_process_output(bx_process_input($_POST['subject'], BX_DATA_TEXT_MULTILINE), BX_DATA_TEXT_MULTILINE);
        $sLetterBody    = bx_process_output(bx_process_input($_POST['body'], BX_DATA_TEXT_MULTILINE), BX_DATA_TEXT_MULTILINE);

        $sLetterBody = $sLetterBody . "\r\n" . '============' . "\r\n" . _t('_from') . ' ' . $sSenderName . "\r\n" . 'with email ' .  $sSenderEmail;

        if (sendMail(getParam('site_email'), $sLetterSubject, $sLetterBody, 0, array(), BX_EMAIL_SYSTEM)) {
            $sActionKey = '_ADM_PROFILE_SEND_MSG';
        } else {
            $sActionKey = '_Email sent failed';
        }
        $sActionText = MsgBox(_t($sActionKey));
    }

    $sForm = $sActionText . $oForm->getCode();
    return DesignBoxContent(_t('_CONTACT_H1'), $sForm, BX_DB_PADDING_DEF);
}

