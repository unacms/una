<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectGridAccounts extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;
	
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->_sModule = 'bx_stripe_connect';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }

    protected function _getCellProfileId($mixedValue, $sKey, $aField, $aRow)
    {
    	return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellLiveDetails($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->__getCellDetails($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellTestDetails($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->__getCellDetails($mixedValue, $sKey, $aField, $aRow);
    }

    protected function __getCellDetails($mixedValue, $sKey, $aField, $aRow)
    {
    	return parent::_getCellDefault(_t((int)$mixedValue != 0 ? '_Yes' : '_No'), $sKey, $aField, $aRow);
    }

    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
    	return $this->__getCellDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellChanged($mixedValue, $sKey, $aField, $aRow)
    {
    	return $this->__getCellDate($mixedValue, $sKey, $aField, $aRow);
    }

    protected function __getCellDate($mixedValue, $sKey, $aField, $aRow)
    {
    	return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _delete($mixedId)
    {
    	return $this->_oModule->deleteAccount($mixedId);
    }
}

/** @} */
