<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once( './../inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

// --------------- page variables and login

$_page['name_index'] = 1;

check_logged();

$_page['header'] = "Merge Langs";
$_page['header_text'] = $_page['header'];

// --------------- page components

$_ni = $_page['name_index'];
$_page_cont[$_ni]['page_main_code'] = PageCompPageMainCode();

// --------------- [END] page components

PageCode();

// --------------- page components functions

/**
 * page code function
 */
function PageCompPageMainCode()
{
    $aForm = array(
        'form_attrs' => array(
            'id' => 'test_email_templates',
            'action' => BX_DOL_URL_ROOT . '_ml.php',
            'method' => 'post',
        ),
        'params' => array (
            'db' => array(
                'submit_name' => 'do_submit',
            ),
        ),
        'inputs' => array(
            'l_new' => array(
                'type' => 'textarea',
                'name' => 'l_new',
                'caption' => 'New Language',
            ),
            'l_old' => array(
                'type' => 'textarea',
                'name' => 'l_old',
                'caption' => 'Old Language',
            ),

            'module_lang_file' => array(
                'type' => 'checkbox',
                'name' => 'module_lang_file',
                'caption' => 'Module language file',
                'value' => 1,
                'checked' => true,
            ),
            'submit' => array(
                'type' => 'submit',
                'name' => 'do_submit',
                'value' => _t('_Submit'),
            ),
        ),
    );

    $oForm = new BxTemplFormView($aForm);
    $oForm->initChecker();

    if ($oForm->isSubmittedAndValid()) {

        $isModuleLangFile = $_POST['module_lang_file'] ? true : false;
        $aLang71 = getLangArray(process_pass_data($_POST['l_new']), $isModuleLangFile);
        $aLang70 = getLangArray(process_pass_data($_POST['l_old']), $isModuleLangFile);

        if (is_array($aLang71) && is_array($aLang70)) {

            $s .= prepareTextarea ('Added Keys', findAddedKeys($aLang71, $aLang70));
            $s .= prepareTextarea ('Changed Keys', findChangedKeys($aLang71, $aLang70));

            $aDeletedKeys = findDeletedKeys($aLang71, $aLang70);
            $s .= prepareTextarea ('Deleted Keys', $aDeletedKeys);
            $s .= prepareTextareaWithDeltedKeys ($aDeletedKeys);

        } else {
            $s = MsgBox("Invalid arrays") . $oForm->getCode();
        }

    } else {

        $s = $oForm->getCode();

    }

    return DesignBoxContent($GLOBALS['_page']['header'], $s, 11);
}

function getLangArray ($s, $isModuleLangFile)
{
    eval($s);
    return $isModuleLangFile ? $aLangContent : $LANG;
}

function findDeletedKeys ($aLang71, $aLang70)
{
    $a = array ();
    foreach ($aLang70 as $k => $v)
        if (!isset($aLang71[$k]))
            $a[$k] = $v;
    return $a;
}

function findAddedKeys ($aLang71, $aLang70)
{
    $a = array ();
    foreach ($aLang71 as $k => $v)
        if (!isset($aLang70[$k]))
            $a[$k] = $v;
    return $a;
}

function findChangedKeys ($aLang71, $aLang70)
{
    $a = array ();
    foreach ($aLang71 as $k => $v)
        if (isset($aLang70[$k]) && $aLang70[$k] != $aLang71[$k])
            $a[$k] = $v;
    return $a;
}

function prepareTextarea ($sTitle, $a)
{
    ksort($a);
    return '<h2>' . $sTitle . '</h2>
        <textarea style="width:800px; height:400px;">' . var_export($a, 1) . '</textarea>
        <hr class="bx-def-hr bx-def-margin-top bx-def-margin-bottom" />';
}

function prepareTextareaWithDeltedKeys ($a)
{
    $iGroupBy = 75;
    ksort($a);
    $aKeys = array_keys($a);
    for ($i = 0; $i < count($aKeys) ; $i+=$iGroupBy)  {
        $sKeys = '';
        $aKeysSlice = array ();
        $aKeysSlice = array_slice($aKeys, $i, $iGroupBy, true);
        foreach ($aKeysSlice as $k)
            $sKeys .= "'" . str_replace("'", "\\'", $k) . "',";
        $sKeys = trim($sKeys, ',');
        $s .= "

DELETE `sys_localization_strings` FROM `sys_localization_strings`, `sys_localization_keys` WHERE `sys_localization_keys`.`ID` = `sys_localization_strings`.`IDKey` AND `sys_localization_keys`.`Key` IN($sKeys);
DELETE FROM `sys_localization_keys` WHERE `Key` IN($sKeys);";
    }

    return "<h2>Deleted Keys SQL</h2>
        <textarea style=\"width:800px; height:300px;\">
-- delete unused language keys
$s
        </textarea>
        <hr class=\"bx-def-hr bx-def-margin-top bx-def-margin-bottom\" />";
}
