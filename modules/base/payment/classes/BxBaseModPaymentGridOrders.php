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

class BxBaseModPaymentGridOrders extends BxBaseModPaymentGridTransactions
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    public function getCode ($isDisplayHeader = true)
    {
        if(empty($this->_aQueryAppend['seller_id']) || $this->_aQueryAppend['seller_id'] != bx_get_logged_profile_id())
            return '';

        return parent::getCode($isDisplayHeader);
    }

    public function performActionCancel()
    {
    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) 
            return echoJson(array());

        $oOrders = $this->_oModule->getObjectOrders();

        $iAffected = 0;
        $aAffected = array();
        foreach($aIds as $iId)
            if($oOrders->cancel($this->_sOrdersType, $iId)) {
                $aAffected[] = $iId;
                $iAffected++;
            }

        echoJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aAffected) : array('msg' => _t($this->_sLangsPrefix . 'err_cannot_perform')));
    }
}

/** @} */
