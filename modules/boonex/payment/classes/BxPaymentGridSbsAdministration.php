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
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct ($aOptions, $oTemplate);

        $this->_sOrdersType = BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION;
    }

    protected function _getCellProvider($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t('_bx_payment_txt_name_' . $mixedValue), $sKey, $aField, $aRow);
    }
}

/** @} */
