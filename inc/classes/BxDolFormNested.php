<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Nested form. It is used in file uploaders to show uploaded file as nested form.
 * @see BxDolUploader
 * @see BxDolForm
 */
class BxDolFormNested extends BxTemplFormView
{
    /**
     * Constructor
     * @param $sName the name of form field from main form, where this nested form is inserted.
     * @param $aForm form array, actually only form inout are needed, all other attributes are taken from parent form automatically.
     * @param $sSubmitName main form submit_name; field name of submit form input to determine if form is submitted or not.
     * @param $oTemplate optional template object
     */
    function __construct($sName, $aForm, $sSubmitName = false, $oTemplate = false)
    {
        if (!isset($aForm['params']['nested_form_template']) || !$aForm['params']['nested_form_template'])
            $aForm['params']['nested_form_template'] = 'uploader_nested_form_wrapper.html';

        $aForm['form_attrs']['id'] = $sName . '_{file_id}';
        $aForm['form_attrs']['method'] = 'specific';
        $aForm['params']['remove_form'] = true;
        $aForm['params']['csrf']['disable'] = true;
        if ($sSubmitName)
            $aForm['params']['db']['submit_name'] = $sSubmitName;
        if (!isset($aForm['inputs'][$sName])) {
            $aForm['inputs'][$sName] = array(
                'type' => 'hidden',
                'name' => $sName . '[]',
                'value' => '{file_id}',
            );
        }

        parent::__construct($aForm, $oTemplate);
    }

    function genForm()
    {
        $sNestedForm = parent::genForm();
        $a = array (
            'nested_form' => $sNestedForm,
        );
        return $this->oTemplate->parseHtmlByName($this->aParams['nested_form_template'], $a);
    }
}

/** @} */
