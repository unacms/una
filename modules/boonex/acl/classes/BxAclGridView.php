<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxAclGridLevels.php');

class BxAclGridView extends BxAclGridLevels
{
	protected $_iOwner;
	protected $_oPayment;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_iOwner = $this->_oModule->_oConfig->getOwner();
        $this->_oPayment = BxDolPayments::getInstance();
    }

    public function getCode($isDisplayHeader = true)
    {
    	return $this->_oPayment->getCartJs() . parent::getCode($isDisplayHeader);
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
