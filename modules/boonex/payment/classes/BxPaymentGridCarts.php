<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 * 
 * @{
 */


class BxPaymentGridCarts extends BxBaseModPaymentGridCarts
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);
    }

	protected function _getCellVendorId($mixedValue, $sKey, $aField, $aRow)
    {
    	return parent::_getCellDefault($this->_oModule->_oTemplate->displayProfileLink(array(
    		'id' => $mixedValue,
    		'name' => $aRow['vendor_name'],
    		'title' => _t('_bx_payment_txt_checkout_to', $aRow['vendor_name']),
    		'link' => bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_CART'), array('seller_id' => (int)$mixedValue))
    	)), $sKey, $aField, $aRow);
    }

	protected function _getCellItemsPrice($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault($this->_sCurrencySign . $mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionContinue ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	unset($a['attr']['bx_grid_action_single']);
    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_CART'), array('seller_id' => $aRow['vendor_id'])) . "','_self');"
    	));

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
    	return '';
    }
    
	protected function _getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
		if(empty($this->_aQueryAppend['client_id']))
			return array();

		$this->_aOptions['source'] = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_SINGLE, $this->_aQueryAppend['client_id']);

        return parent::_getDataArray($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _delete($mixedId)
    {
		return $this->_oCart->serviceDeleteFromCart($mixedId);
    }
}

/** @} */
