<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 * 
 * @{
 */


class BxAclGridView extends BxTemplGrid
{
	protected $MODULE;
	protected $_oModule;

	protected $_iOwner;
	protected $_oPayment;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct ($aOptions, $this->_oModule->_oTemplate);

        $this->_iOwner = $this->_oModule->_oConfig->getOwner();
        $this->_oPayment = BxDolPayments::getInstance();
    }

    public function getCode($isDisplayHeader = true)
    {
    	return $this->_oPayment->getCartJs() . parent::getCode($isDisplayHeader);
    }

	protected function _getCellPeriodUnit($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = _t('_bx_acl_pre_values_' . $mixedValue);
    	return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellPrice($mixedValue, $sKey, $aField, $aRow)
    {
    	$aCurrency = $this->_oModule->_oConfig->getCurrency();

        return parent::_getCellDefault($aCurrency['sign'] . $mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getActionBuy ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$aJs = $this->_oPayment->getAddToCartJs($this->_iOwner, $this->MODULE, $aRow['id'], 1, true);
    	if(!empty($aJs) && is_array($aJs)) {
    		list($sJsCode, $sJsMethod) = $aJs;

    		$a['attr'] = array(
    			'onclick' => $sJsMethod
    		);
    	}

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getActionSubscribe ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$aJs = $this->_oPayment->getSubscribeJs($this->_iOwner, '', $this->MODULE, $aRow['id'], 1);
		if(!empty($aJs) && is_array($aJs)) {
			list($sJsCode, $sJsMethod) = $aJs;

    		$a['attr'] = array(
    			'onclick' => $sJsMethod
    		);
		}

    	return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }
}

/** @} */
