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

class BxPaymentFormInvoices extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sModule = 'bx_payment';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        if(isset($this->aInputs['status'])) {
            $aStatuses = array(BX_PAYMENT_INV_STATUS_UNPAID, BX_PAYMENT_INV_STATUS_PAID, BX_PAYMENT_INV_STATUS_OVERDUE);

            $this->aInputs['status']['values'] = array();
            foreach($aStatuses as $sStatus)
                $this->aInputs['status']['values'][] = array('key' => $sStatus, 'value' => _t('_bx_payment_txt_status_' . $sStatus));
        }
    }
}

/** @} */
