<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

bx_import('BxDolStudioForm');

class BxDolStudioFormsField extends BxDol
{
    protected $oDb;
    protected $aTypes = array();
    protected $aTypesRelated = array();

    protected $sType = '';
    protected $aCheckFunctions = array();
    protected $sDbPass = '';
    protected $aDbPassDependency = array();

    protected $aParams = array();
    protected $aField = array();
    protected $iFieldNameMaxLen;
    protected $aFormObject = array();
    
    public function __construct($aParams = array(), $aField = array())
    {
        parent::__construct();

        $this->oDb = new BxDolStudioFormsQuery();

        $this->aTypes = array(
        	'block_header' => array('add' => 1), 
        	'block_end' => array('add' => 1), 
        	'text' => array('add' => 1), 
        	'datepicker' => array('add' => 1), 
        	'datetime' => array('add' => 1), 
        	'number' => array('add' => 1), 
            'time' => array('add' => 1), 
        	'checkbox' => array('add' => 1), 
        	'password' => array('add' => 1), 
        	'slider' => array('add' => 1), 
        	'doublerange' => array('add' => 1), 
        	'hidden' => array('add' => 1), 
        	'switcher' => array('add' => 1), 
        	'reset' => array('add' => 1), 
        	'submit' => array('add' => 1), 
        	'textarea' => array('add' => 1), 
        	'select' => array('add' => 1), 
        	'select_multiple' => array('add' => 1), 
        	'checkbox_set' => array('add' => 1), 
        	'radio_set' => array('add' => 1), 
        	'value' => array('add' => 1), 
        	'file' => array('add' => 1), 
        	'files' => array('add' => 1),
            'nested_form' => array('add' => 1), 
        	'captcha' => array('add' => 0),
        	'location' => array('add' => 0), 
        	'custom' => array('add' => 0)
        );

        $this->aTypesRelated = array(
            'select_multiple' => array('types' => array('select_multiple', 'checkbox_set'), 'reload_on_change' => 0),
            'checkbox_set' => array('types' => array('select_multiple', 'checkbox_set'), 'reload_on_change' => 0),

            'select' => array('types' => array('select', 'radio_set'), 'reload_on_change' => 0),
            'radio_set' => array('types' => array('select', 'radio_set'), 'reload_on_change' => 0),

            'datepicker' => array('types' => array('datepicker', 'datetime', 'dateselect'), 'reload_on_change' => 1),
            'datetime' => array('types' => array('datepicker', 'datetime', 'dateselect'), 'reload_on_change' => 1),
            'dateselect' => array('types' => array('datepicker', 'datetime', 'dateselect'), 'reload_on_change' => 1),

            'checkbox' => array('types' => array('checkbox', 'switcher'), 'reload_on_change' => 0),
            'switcher' => array('types' => array('checkbox', 'switcher'), 'reload_on_change' => 0),

            'number' => array('types' => array('number', 'slider'), 'reload_on_change' => 1),
            'slider' => array('types' => array('number', 'slider'), 'reload_on_change' => 1),
        );

        $this->aParams = $aParams;
        
        $this->aFormObject = BxDolFormQuery::getFormObject ($this->aParams['object']); 
        if ($this->isNested()){
            $this->aTypes['nested_form']['add'] = 0;
            $this->aTypes['file']['add'] = 0;
            $this->aTypes['files']['add'] = 0;
        }
       
        $this->aField = $aField;
        $this->iFieldNameMaxLen = 32;
    }

    public function init()
    {
    	if(isset($this->aParams['object']) && isset($this->aParams['display'])) {
            $aForm = array();
            $this->oDb->getForms(array('type' => 'by_object_display', 'object' => $this->aParams['object'], 'display' => $this->aParams['display']), $aForm, false);

            $this->aParams['table'] = $aForm['table'];
        }
    }

    public function canAdd()
    {
        return isset($this->aParams['table']) && !empty($this->aParams['table']);
    }

    public function isNested()
    {
        return $this->aFormObject['parent_form'] != '' ? true : false;
    }
    
    /**
     * Checks whether the field exists in connected table.
     * @param type $sName - field name
     * @return boolean - check result
     */
    public function isField($sName)
    {
        if(!isset($this->aParams['table_alter']) || $this->aParams['table_alter'] !== true)
            return false;

        if(!isset($this->aParams['table']))
            return false;

        return $this->oDb->isFieldExists($this->aParams['table'], $sName);
    }

    public function alterAdd($sName)
    {
        if(!isset($this->aParams['table_alter']) || $this->aParams['table_alter'] !== true)
            return true;

        if(!isset($this->aParams['table'], $this->aParams['table_field_type']))
            return false;

        return $this->oDb->alterAdd($this->aParams['table'], $sName, $this->aParams['table_field_type']);
    }

    public function alterChange($sNameOld, $sNameNew)
    {
        if(!isset($this->aParams['table_alter']) || $this->aParams['table_alter'] !== true)
            return true;

        if(!isset($this->aParams['table'], $this->aParams['table_field_type']))
            return false;

        return $this->oDb->alterChange($this->aParams['table'], $sNameOld, $sNameNew, $this->aParams['table_field_type']);
    }

    public function alterRemove($sName)
    {
        if(!isset($this->aParams['table_alter']) || $this->aParams['table_alter'] !== true)
            return true;

        if(!isset($this->aParams['table']))
            return false;

        return $this->oDb->alterRemove($this->aParams['table'], $sName);
    }

    protected function getSystemName($sValue)
    {
        return BxDolStudioUtils::getSystemName($sValue);
    }

    protected function getClassName($sValue)
    {
        return BxDolStudioUtils::getClassName($sValue);
    }

    protected function addInArray($aInput, $sKey, $aValues, $bAddAfter = true)
    {
        return BxDolStudioUtils::addInArray($aInput, $sKey, $aValues, $bAddAfter);
    }
}

/** @} */
