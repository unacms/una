<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 * 
 * @{
 */


class BxPaymentGridSbsAdministration extends BxBaseModPaymentGridOrders
{
    protected $_sManageType;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sOrdersType = BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION;
        $this->_sManageType = 'administration';
    }

    public function performActionCancel()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) 
        	return echoJson(array());

		$oSubscriptions = $this->_oModule->getObjectSubscriptions();

		$iAffected = 0;
		$aAffected = array();
		foreach($aIds as $iId)
			if($oSubscriptions->cancel($iId)) {
				$aAffected[] = $iId;
            	$iAffected++;
			}

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aAffected) : array('msg' => _t($this->_sLangsPrefix . 'err_cannot_perform')));
    }

    protected function _getCellProvider($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_payment_txt_name_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellSubscriptionId($mixedValue, $sKey, $aField, $aRow)
    {
        $aItems = $this->_oModule->_oConfig->descriptorsM2A($aRow['items']);
        if(!is_array($aItems) || count($aItems) != 1)
            return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);

        $aItem = array_shift($aItems);
        $aInfo = BxDolService::call((int)$aItem['module_id'], 'get_payment_data');
        if(!empty($aInfo['url_browse_order_' . $this->_sManageType]))
            $mixedValue = $this->_oModule->_oTemplate->displayLink('link', array(
                'href' => bx_replace_markers($aInfo['url_browse_order_' . $this->_sManageType], array(
                    'order' => $mixedValue
                )),
                'title' => $mixedValue,
                'content' => $mixedValue
            ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
        
    protected function _getCellPaid($mixedValue, $sKey, $aField, $aRow)
    {
        $aStates = array(0 => 'no', 1 => 'yes');
        return parent::_getCellDefault(_t('_bx_payment_txt_' . $aStates[(int)$mixedValue]), $sKey, $aField, $aRow);
    }
}

/** @} */
