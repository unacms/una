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
    protected $_iNow;
    protected $_sManageType;

    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);

        $this->_iNow = time();
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
                'title' => bx_html_attribute($mixedValue),
                'content' => $mixedValue
            ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellDateAdd($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellDefaultDateTime($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellDateNext($mixedValue, $sKey, $aField, $aRow)
    {
        if(empty($mixedValue) || (is_numeric($mixedValue) && (int)$mixedValue < $this->_iNow))
            return parent::_getCellDefault(_t('_uknown'), $sKey, $aField, $aRow);

        return $this->_getCellDefaultDateTime($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellStatus($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_payment_txt_status_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oTemplate->addJsTranslation(array(
            '_bx_payment_msg_confirm_cancelation',
            '_bx_payment_txt_unsubscribe_yes',
            '_bx_payment_txt_unsubscribe_no'
        ));
    }

    protected function _delete ($mixedId)
    {
        $aSubscription = $this->_oModule->_oDb->getSubscription(array('type' => 'pending_id', 'pending_id' => $mixedId));
        if(empty($aSubscription) || !is_array($aSubscription))
            return false;

        return $this->_oModule->_oDb->deleteSubscription($aSubscription['id'], 'delete');
    }
}

/** @} */
