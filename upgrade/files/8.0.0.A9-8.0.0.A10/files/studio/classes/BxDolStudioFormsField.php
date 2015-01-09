<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxDol');
bx_import('BxDolGrid');
bx_import('BxDolStudioFormsQuery');

class BxDolStudioFormsField extends BxDol
{
    protected $oDb;
    protected $aTypes = array();
    protected $aTypesRelated = array();

    protected $sType = '';
    protected $aCheckFunctions = array();
    protected $sDbPass = '';

    protected $aParams = array();
    protected $aField = array();

    public function __construct($aParams = array(), $aField = array())
    {
        parent::__construct();

        $this->oDb = new BxDolStudioFormsQuery();
        $this->aTypes = array('block_header', 'text', 'datepicker', 'datetime', 'number', 'checkbox', 'password', 'slider', 'doublerange', 'hidden', 'switcher', 'reset', 'submit', 'textarea', 'select', 'select_multiple', 'checkbox_set', 'radio_set', 'value', 'captcha', 'file', 'files', 'custom');
        $this->aTypesRelated = array(
            'select_multiple' => array('types' => array('select_multiple', 'checkbox_set'), 'reload_on_change' => 0),
            'checkbox_set' => array('types' => array('select_multiple', 'checkbox_set'), 'reload_on_change' => 0),

            'select' => array('types' => array('select', 'radio_set'), 'reload_on_change' => 0),
            'radio_set' => array('types' => array('select', 'radio_set'), 'reload_on_change' => 0),

            'datepicker' => array('types' => array('datepicker', 'datetime'), 'reload_on_change' => 1),
            'datetime' => array('types' => array('datepicker', 'datetime'), 'reload_on_change' => 1),

            'checkbox' => array('types' => array('checkbox', 'switcher'), 'reload_on_change' => 0),
            'switcher' => array('types' => array('checkbox', 'switcher'), 'reload_on_change' => 0),

            'number' => array('types' => array('number', 'slider'), 'reload_on_change' => 1),
            'slider' => array('types' => array('number', 'slider'), 'reload_on_change' => 1),
        );

        $this->aParams = $aParams;
        $this->aField = $aField;

        if(isset($this->aParams['object']) && isset($this->aParams['display'])) {
            $aForm = array();
            $this->oDb->getForms(array('type' => 'by_object_display', 'object' => $this->aParams['object'], 'display' => $this->aParams['display']), $aForm, false);

            $this->aParams['table'] = $aForm['table'];
        }
    }

    function canAdd()
    {
        return isset($this->aParams['table']) && !empty($this->aParams['table']);
    }

    function alterAdd($sName)
    {
        if(!isset($this->aParams['table'], $this->aParams['table_alter'], $this->aParams['table_field_type']) || $this->aParams['table_alter'] !== true)
            return '';

        return $this->oDb->alterAdd($this->aParams['table'], $sName, $this->aParams['table_field_type']);
    }

    function alterRemove($sName)
    {
        if(!isset($this->aParams['table'], $this->aParams['table_alter']) || $this->aParams['table_alter'] !== true)
            return '';

        return $this->oDb->alterRemove($this->aParams['table'], $sName);
    }

    protected function getSystemName($sValue)
    {
        bx_import('BxDolStudioUtils');
        return BxDolStudioUtils::getSystemName($sValue);
    }

    protected function getClassName($sValue)
    {
        bx_import('BxDolStudioUtils');
        return BxDolStudioUtils::getClassName($sValue);
    }

    protected function addInArray($aInput, $sKey, $aValues)
    {
        bx_import('BxDolStudioUtils');
        return BxDolStudioUtils::addInArray($aInput, $sKey, $aValues);
    }
}

/** @} */
