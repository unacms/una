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

class BxPaymentProviderOffline extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);
    }

    public function getCheckoutUrl($aParams = array())
    {
        return $this->_oModule->_oConfig->getUrl('URL_CHECKOUT_OFFLINE', $aParams, $this->_bUseSsl);
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

        $iSellerId = (int)$aCartInfo['vendor_id'];

        switch($aPending['type']) {
            case BX_PAYMENT_TYPE_SINGLE:
                $aFormData = array(
                    'currency_code' => $aCartInfo['vendor_currency_code'],
                    'currency_sign' => $aCartInfo['vendor_currency_sign'],
                    'seller' => $iSellerId,
                    'amount' => (float)$aCartInfo['items_price'],
                    'items_count' => count($aCartInfo['items']),
                    'return_url' => $this->_oModule->getObjectCart()->serviceGetCartUrl($iSellerId),
                );

                $iIndex = 0;
                foreach($aCartInfo['items'] as $aItem) {
                    $aFormData['item_title_' . $iIndex] = bx_process_output($aItem['title']);
                    $aFormData['item_quantity_' . $iIndex] = $aItem['quantity'];

                    $iIndex += 1;
                }

                $this->_oModule->_oTemplate->displayPageCodeRedirect($this->getCheckoutUrl(), $aFormData);
                exit;

            case BX_PAYMENT_TYPE_RECURRING:
                return array(
                    'code' => 1, 
                    'message' => $this->_sLangsPrefix . 'cdt_err_not_available'
                );
        }
    }

    public function finalizeCheckout(&$aData)
    {
        return array('code' => 1, 'message' => $this->_sLangsPrefix . 'cdt_err_not_available');
    }

    public function finalizedCheckout()
    {
        return array('code' => 1, 'message' => $this->_sLangsPrefix . 'cdt_err_not_available');
    }
}

/** @} */
