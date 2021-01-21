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

class BxBaseModPaymentGridCommissions extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sLangsPrefix;

    protected $_aAclLevels;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');

        $this->_aAclLevels = BxDolAcl::getInstance()->getMemberships(false, true, true, true);
    }

    protected function _getCellAclId($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = isset($this->_aAclLevels[$mixedValue]) > 0 ? $this->_aAclLevels[$mixedValue] : _t('_all');

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellPercentage($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = (float)$mixedValue > 0 ? $mixedValue . '%' : '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellInstallment($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = (float)$mixedValue > 0 ? _t_format_currency($mixedValue) : '';

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
}

/** @} */
