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
	protected $_bTypeSingle;
	protected $_bTypeRecurring;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_iOwner = $this->_oModule->_oConfig->getOwner();
        $this->_oPayment = BxDolPayments::getInstance();
        $this->_bTypeSingle = $this->_oPayment->isAcceptingPayments($this->_iOwner, BX_PAYMENT_TYPE_SINGLE);
        $this->_bTypeRecurring = $this->_oPayment->isAcceptingPayments($this->_iOwner, BX_PAYMENT_TYPE_RECURRING);
    }

    public function getCode($isDisplayHeader = true)
    {
    	return $this->_oPayment->getCartJs() . parent::getCode($isDisplayHeader);
    }

	protected function _getActionBuy ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bTypeSingle || $this->_bTypeRecurring)
            return '';

    	$aJs = $this->_oPayment->getAddToCartJs($this->_iOwner, $this->MODULE, $aRow['id'], 1, true);
    	if(!empty($aJs) && is_array($aJs)) {
    		list($sJsCode, $sJsMethod) = $aJs;

    		$a['attr'] = array(
    		    'title' => bx_html_attribute(_t('_bx_acl_grid_action_buy_title')),
    			'onclick' => $sJsMethod
    		);
    	}

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getActionSubscribe ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_bTypeRecurring)
            return '';

    	$aJs = $this->_oPayment->getSubscribeJs($this->_iOwner, '', $this->MODULE, $aRow['id'], 1);
		if(!empty($aJs) && is_array($aJs)) {
			list($sJsCode, $sJsMethod) = $aJs;

    		$a['attr'] = array(
    			'title' => bx_html_attribute(_t('_bx_acl_grid_action_subscribe_title')),
    			'onclick' => $sJsMethod
    		);
		}

    	return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }
}

/** @} */
