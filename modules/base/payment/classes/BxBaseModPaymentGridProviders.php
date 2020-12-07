<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModPaymentGridProviders extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    protected function _getCellForVisitor($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getCellValue($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellForSingle($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getCellValue($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellForRecurring($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getCellValue($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSingleSeller($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getCellValue($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellTimeTracker($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_getCellValue($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellValue($mixedValue)
    {
        return _t((int)$mixedValue != 0 ? '_Yes' : '_No');
    }
    
}

/** @} */
