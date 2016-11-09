<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioFormsFields extends BxTemplStudioGrid
{
    protected $sModule = '';
    protected $sObject = '';
    protected $sDisplay = '';

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioFormsQuery();

        $sModule = bx_get('module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sObject = bx_get('object');
        if(!empty($sObject)) {
            $this->sObject = bx_process_input($sObject);
            $this->_aQueryAppend['object'] = $this->sObject;
        }

        $sDisplay = bx_get('display');
        if(!empty($sDisplay)) {
            $this->sDisplay = bx_process_input($sDisplay);
            $this->_aQueryAppend['display'] = $this->sDisplay;
        }
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->sObject) || empty($this->sDisplay))
            return array();

        $this->oDb->checkInputsInDisplays($this->sObject, $this->sDisplay);

        $this->_aOptions['source'] = $this->oDb->prepareAsString($this->_aOptions['source'], $this->sObject, $this->sDisplay);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
