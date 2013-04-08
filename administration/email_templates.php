<?php

// TODO: remake according to new design and principles, site setup part leave in admin and remake other functionality move to user part

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -----------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2006 BoonEx Group
*     website              : http://www.boonex.com/
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software. This work is licensed under a Creative Commons Attribution 3.0 License.
* http://creativecommons.org/licenses/by/3.0/
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the Creative Commons Attribution 3.0 License for more details.
* You should have received a copy of the Creative Commons Attribution 3.0 License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

require_once( '../inc/header.inc.php' );

$GLOBALS['iAdminPage'] = 1;

require_once( BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'admin_design.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'utils.inc.php' );
require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

bx_import('BxDolAdminSettings');
bx_import('BxTemplSearchResult');

$logged['admin'] = member_auth( 1, true, true );

$oSettings = new BxDolAdminSettings(4);

//--- Process submit ---//
$mixedResultSettings = '';
$mixedResultTemplates = '';
if(isset($_POST['save']) && isset($_POST['cat'])) {
    $mixedResultSettings = $oSettings->saveChanges($_POST);
} elseif(isset($_POST['action']) && $_POST['action'] == 'get_translations') {
    $aTranslation = $GLOBALS['MySQL']->getRow("SELECT `Subject` AS `subject`, `Body` AS `body` FROM `sys_email_templates` WHERE `Name`='" . process_db_input($_POST['templ_name']) . "' AND `LangID`='" . (int)$_POST['lang_id'] . "' LIMIT 1");
    if(empty($aTranslation))
        $aTranslation = $GLOBALS['MySQL']->getRow("SELECT `Subject` AS `subject`, `Body` AS `body` FROM `sys_email_templates` WHERE `Name`='" . process_db_input($_POST['templ_name']) . "' AND `LangID`='0' LIMIT 1");

    $oJson = new Services_JSON();
    echo $oJson->encode(array('subject' => $aTranslation['subject'], 'body' => $aTranslation['body']));
    exit;
}

$iNameIndex = 8;
$_page = array(
    'name_index' => $iNameIndex,
    'css_name' => array('forms_adv.css', 'settings.css'),
    'js_name' => array('email_templates.js'),
    'header' => _t('_adm_page_cpt_email_templates'),
);
$_page_cont[$iNameIndex] = array(
    'page_code_settings' => PageCodeSettings($mixedResultSettings),
    'page_code_templates' => PageCodeTemplates($mixedResultTemplates),
);

PageCodeAdmin();

function PageCodeSettings($mixedResult) {

    $sResult = $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $GLOBALS['oSettings']->getForm()));
    if($mixedResult !== true && !empty($mixedResult))
        $sResult = $mixedResult . $sResult;

    return DesignBoxAdmin(_t('_adm_box_cpt_email_settings'), $sResult);
}
function PageCodeTemplates($mixedResult) {

    $aForm = array(
        'form_attrs' => array(
            'id' => 'adm-email-templates',
            'action' => $GLOBALS['site']['url_admin'] . 'email_templates.php',
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ),
        'params' => array (
                'db' => array(
                    'table' => 'sys_email_templates',
                    'key' => 'ID',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'adm-emial-templates-save'
                ),
            ),
        'inputs' => array ()
    );

    $aLanguages = $GLOBALS['MySQL']->getAll("SELECT `ID` AS `id`, `Title` AS `title` FROM `sys_localization_languages`");

    $aLanguageChooser = array(array('key' => 0, 'value' => 'default'));
    foreach($aLanguages as $aLanguage)
        $aLanguageChooser[] = array('key' => $aLanguage['id'], 'value' => $aLanguage['title']);

    $sLanguageCpt = _t('_adm_txt_email_language');
    $sSubjectCpt = _t('_adm_txt_email_subject');
    $sBodyCpt = _t('_adm_txt_email_body');

    $aEmails = $GLOBALS['MySQL']->getAll("SELECT `ID` AS `id`, `Name` AS `name`, `Subject` AS `subject`, `Body` AS `body`, `Desc` AS `description` FROM `sys_email_templates` WHERE `LangID`='0' ORDER BY `ID`");
    foreach($aEmails as $aEmail) {
        $aForm['inputs'] = array_merge($aForm['inputs'], array(
            $aEmail['name'] . '_Beg' => array(
                'type' => 'block_header',
                'caption' => $aEmail['description'],
                'collapsable' => true,
                'collapsed' => true
            ),
            $aEmail['name'] . '_Language' => array(
                'type' => 'select',
                'name' => $aEmail['name'] . '_Language',
                'caption' => $sLanguageCpt,
                'value' =>  0,
                'values' => $aLanguageChooser,
                'db' => array (
                    'pass' => 'Int',
                ),
                'attrs' => array(
                    'onchange' => "javascript:getTranslations(this)"
                )
            ),
            $aEmail['name'] . '_Subject' => array(
                'type' => 'text',
                'name' => $aEmail['name'] . '_Subject',
                'caption' => $sSubjectCpt,
                'value' => $aEmail['subject'],
                'db' => array (
                    'pass' => 'Xss',
                ),
            ),
            $aEmail['name'] . '_Body' => array(
                'type' => 'textarea',
                'name' => $aEmail['name'] . '_Body',
                'caption' => $sBodyCpt,
                'value' => $aEmail['body'],
                'db' => array (
                    'pass' => 'XssHtml',
                ),
            ),
            $aEmail['name'] . '_End' => array(
                'type' => 'block_end'
            )
        ));
    }

    $aForm['inputs']['adm-emial-templates-save'] = array(
        'type' => 'submit',
        'name' => 'adm-emial-templates-save',
        'value' => _t('_adm_btn_email_save'),
    );

    $oForm = new BxTemplFormView($aForm);
    $oForm->initChecker();

    $sResult = "";
    if($oForm->isSubmittedAndValid()) {
        $iResult = 0;
        foreach($aEmails as $aEmail) {
            $iEmailId = (int)$GLOBALS['MySQL']->getOne("SELECT `ID` FROM `sys_email_templates` WHERE `Name`='" . process_db_input($aEmail['name']) . "' AND `LangID`='" . (int)$_POST[$aEmail['name'] . '_Language'] . "' LIMIT 1");
            if($iEmailId != 0)
                $iResult += (int)$GLOBALS['MySQL']->query("UPDATE `sys_email_templates` SET `Subject`='" . process_db_input($_POST[$aEmail['name'] . '_Subject']) . "', `Body`='" . process_db_input($_POST[$aEmail['name'] . '_Body']) . "' WHERE `ID`='" . $iEmailId . "'");
            else
                $iResult += (int)$GLOBALS['MySQL']->query("INSERT INTO `sys_email_templates` SET `Name`='" . process_db_input($aEmail['name']) . "', `Subject`='" . process_db_input($_POST[$aEmail['name'] . '_Subject']) . "', `Body`='" . process_db_input($_POST[$aEmail['name'] . '_Body']) . "', `LangID`='" . (int)$_POST[$aEmail['name'] . '_Language'] . "'");
        }

        $sResult .= MsgBox(_t($iResult > 0 ? "_adm_txt_email_success_save" : "_adm_txt_email_nothing_changed"), 3);
    }
    $sResult .= $oForm->getCode();

    return DesignBoxAdmin(_t('_adm_box_cpt_email_templates'), $GLOBALS['oAdmTemplate']->parseHtmlByName('email_templates.html', array(
        'content' => stripslashes($sResult),
        'loading' => LoadingBox('adm-email-loading')
    )));
}

?>
