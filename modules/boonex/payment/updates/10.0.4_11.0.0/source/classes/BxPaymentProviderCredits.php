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

/**
 * Credits payment provider can be used when 
 * Credits module is installed and active.
 */

class BxPaymentProviderCredits extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
    protected $_sModuleCredits;

    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_sModuleCredits = 'bx_credits';
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $bRecurring = false, $iRecurringDays = 0)
    {
        if(!BxDolModuleQuery::getInstance()->isEnabledByName($this->_sModuleCredits)) {
            $this->_oModule->_oTemplate->displayPageCodeError('_bx_payment_cdt_err_not_available');
            exit;
        }

        $iSellerId = (int)$aCartInfo['vendor_id'];
        $sCheckoutUrl = BxDolService::call($this->_sModuleCredits, 'get_checkout_url');
        $sReturnDataUrl = $this->getReturnDataUrl($iSellerId);
        

        $aFormData = array(
            'currency_code' => $aCartInfo['vendor_currency_code'],
            'currency_sign' => $aCartInfo['vendor_currency_sign'],
            'seller' => $iSellerId,
            'amount' => (float)$aCartInfo['items_price'],
            'items_count' => count($aCartInfo['items']),
            'custom' => $this->_constructCustomData($aCartInfo['vendor_id'], $iPendingId),
            'return_data_url' => $sReturnDataUrl,
        );

        $iIndex = 0;
        foreach($aCartInfo['items'] as $aItem) {
            $aFormData['item_title_' . $iIndex] = bx_process_output($aItem['title']);
            $aFormData['item_quantity_' . $iIndex] = $aItem['quantity'];

            $iIndex += 1;
        }

        $this->_oModule->_oTemplate->displayPageCodeRedirect($sCheckoutUrl, $aFormData);
        exit;
    }

    public function finalizeCheckout(&$aData)
    {
        $aResult = $this->_finalizeCheckout($aData);
        if((int)$aResult['code'] != BX_PAYMENT_RESULT_SUCCESS) {
            $this->log('Finalize Checkout: Failed');
            $this->log($aData);
            $this->log($aResult);
        }

        if(!empty($aResult['pending_id']))
            $this->_oModule->_oDb->updateOrderPending($aResult['pending_id'], array(
                'order' => isset($aData['o']) ? $aData['o'] : '',
                'error_code' => $aResult['code'],
                'error_msg' => _t($aResult['message'])
            ));

        return $aResult;
    }

    protected function _finalizeCheckout(&$aData)
    {
        list($iSellerId, $iPendingId) = $this->_deconstructCustomData($aData['c']);

        if(empty($this->_aOptions) && !empty($iPendingId))
            $this->_aOptions = $this->getOptionsByPending((int)$iPendingId);

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aPending) || !is_array($aPending))
            return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_not_found_pending');

        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 2, 'message' => $this->_sLangsPrefix . 'err_already_processed');

        if(empty($this->_aOptions) || !BxDolModuleQuery::getInstance()->isEnabledByName($this->_sModuleCredits))
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'err_incorrect_provider', 'pending_id' => $iPendingId);

        $sOrder = $aData['o'];
        if(!BxDolService::call($this->_sModuleCredits, 'validate_checkout', array((int)$aPending['seller_id'], (int)$aPending['client_id'], (float)$aPending['amount'], $sOrder)))
            return array('code' => 4, 'message' => $this->_sLangsPrefix . 'cdt_err_wrong_transaction', 'pending_id' => $iPendingId);

        $oClient = BxDolProfile::getInstance($aPending['client_id']);
        return array(
            'code' => BX_PAYMENT_RESULT_SUCCESS, 
            'message' => $this->_sLangsPrefix . 'cdt_msg_processed',
            'pending_id' => $iPendingId,
            'client_name' => $oClient->getDisplayName(),
            'client_email' => $oClient->getAccountObject()->getEmail(),
            'paid' => true
        );
    }

    protected function _constructCustomData()
    {
        $aParams = func_get_args();
        return urlencode(base64_encode(implode('|', $aParams)));
    }

    protected function _deconstructCustomData($data)
    {
        return explode('|', base64_decode(urldecode($data)));
    }
}

/** @} */
