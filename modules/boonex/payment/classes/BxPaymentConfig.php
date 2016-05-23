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

class BxPaymentConfig extends BxBaseModPaymentConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $sBaseUrl = BX_DOL_URL_ROOT . $this->getBaseUri();

        $this->CNF = array_merge($this->CNF, array(
        	'URL_JOIN' => 'page.php?i=payment-join',
        	'URL_CARTS' => 'page.php?i=payment-carts',
        	'URL_CART' => 'page.php?i=payment-cart',
        	'URL_CART_CHECKOUT' => $sBaseUrl . 'initialize_checkout/' . BX_PAYMENT_TYPE_SINGLE . '/',
        	'URL_HISTORY' => 'page.php?i=payment-history',
        	'URL_ORDERS' => 'page.php?i=payment-orders',
        	'URL_DETAILS' => 'page.php?i=payment-details',
        	'URL_RETURN' => 'page.php?i=payment-cart',
        	'URL_RETURN_DATA' => $sBaseUrl . 'finalize_checkout/',
        	'URL_NOTIFY' => $sBaseUrl . 'notify/',

        	'KEY_ARRAY_PRICE_SINGLE' => 'price_single',
        	'KEY_ARRAY_PRICE_RECURRING' => 'price_recurring',
        
	        // some language keys
        	'T' => array(
        		'MSG_ITEM_ADDED' => '_bx_payment_msg_item_added',
        		'MSG_ITEM_DELETED' => '_bx_payment_msg_item_deleted',
        		'ERR_WRONG_DATA' => '_bx_payment_err_wrong_data',
        		'ERR_REQUIRED_LOGIN' => '_bx_payment_err_required_login',
        		'ERR_NOT_ACCEPT_PAYMENTS' => '_bx_payment_err_not_accept_payments',
        		'ERR_SELF_PURCHASE' => '_bx_payment_err_self_purchase',
        		'ERR_INACTIVE_VENDOR' => '_bx_payment_err_inactive_vendor',
        		'ERR_UNKNOWN_VENDOR' => '_bx_payment_err_unknown_vendor',

				'BLOCK_TITLE_CART' => '_bx_payment_page_block_title_cart',

        		'POPUP_PROVIDERS_SELECT' => '_bx_payment_popup_title_crd_providers_select', 

        		'TXT_CART_PROVIDER' => '_bx_payment_txt_cart_'
        	)
        ));

        $this->_aHtmlIds = array(
        	'cart' => array(
        		'providers_select' => $this->_sName . '-cart-providers-select-',
        	),
        	'history' => array(
        		'order_history_view' => $this->_sName . '-order-view-history',
        	),
        	'pending' => array(
        		'order_pending_view' => $this->_sName . '-order-view-pending',
        		'order_pending_process' => $this->_sName . '-order-process',
        	),
        	'processed' => array(
        		'order_processed_view' => $this->_sName . '-order-view-processed',
        		'order_processed_add' => $this->_sName . '-order-add',
        		'order_processed_client_id' => $this->_sName . '-oa-client-id',
        		'order_processed_client' => $this->_sName . '-oa-client',
        		'order_processed_items' => $this->_sName . '-oa-items',	
        	),
        );

        $this->_aPerPage = array(
        	'orders' => 10,
        	'history' => 10
        );
        $this->_aHandlers = array();

        $this->_aJsClasses = array(
        	'cart' => 'BxPaymentCart',
        	'history' => 'BxPaymentOrders',
        	'pending' => 'BxPaymentOrders',
        	'processed' => 'BxPaymentOrders',
        );

        $this->_aJsObjects = array(
        	'cart' => 'oPaymentCart',
        	'history' => 'oPaymentOrders',
        	'pending' => 'oPaymentOrders',
        	'processed' => 'oPaymentOrders',
        );
    }

	public function getLicense()
    {
        list($fMilliSec, $iSec) = explode(' ', microtime());
        $fSeed = (float)$iSec + ((float)$fMilliSec * 100000);
        srand($fSeed);

        $sResult = '';
        for($i=0; $i < 16; ++$i) {
            switch(rand(1,2)) {
                case 1:
                    $c = chr(rand(ord('A'),ord('Z')));
                    break;
                case 2:
                    $c = chr(rand(ord('0'),ord('9')));
                    break;
            }
            $sResult .= $c;
        }

        return $sResult;
    }

    public function getPrice($sType, $aItem)
    {
    	$fPrice = 0;

		switch($sType) {
			case BX_PAYMENT_TYPE_SINGLE:
				$fPrice = $aItem[$this->getKey('KEY_ARRAY_PRICE_SINGLE')];
				break;

			case BX_PAYMENT_TYPE_RECURRING:
				$fPrice = $aItem[$this->getKey('KEY_ARRAY_PRICE_RECURRING')];
				break;
		}

		return (float)$fPrice;
    }

    public function getModuleId($mixedId)
    {
    	if(is_numeric($mixedId))
    		return (int)$mixedId;

		if(is_string($mixedId)) {
            $aInfo = $this->_oDb->getModuleByName($mixedId);
            if(!is_array($aInfo) || empty($aInfo['id']))
            	$aInfo = $this->_oDb->getModuleByUri($mixedId);

            if(is_array($aInfo) && !empty($aInfo['id']))
            	return (int)$aInfo['id'];
        } 

        return 0;
    }

    public function descriptorA2S($a) 
    {
    	return implode($this->getDivider('DIVIDER_DESCRIPTOR'), $a);
    }

	public function descriptorS2A($s) 
    {
    	return explode($this->getDivider('DIVIDER_DESCRIPTOR'), $s);
    }

	/**
     * Conver items to array with necessary structure.
     *
     * @param  string/array $mixed - string with cart items divided with (:) or an array of cart items.
     * @return array        with items.
     */
    public function descriptorsM2A($mixed)
    {
        $aResult = array();

        if(is_string($mixed))
           $aItems = explode($this->getDivider('DIVIDER_DESCRIPTORS'), $mixed);
        else if(is_array($mixed))
           $aItems = $mixed;
        else
            $aItems = array();

        foreach($aItems as $sItem) {
            $aItem = $this->descriptorS2A($sItem);
            $aResult[] = array('vendor_id' => $aItem[0], 'module_id' => $aItem[1], 'item_id' => $aItem[2], 'item_count' => $aItem[3]);
        }

        return $aResult;
    }

	public function http2https($s)
    {
    	if(strncmp($s, 'https://', 8) == 0)
    		return $s;

        return 'https://' . bx_ltrim_str($s, 'http://');
    }
}

/** @} */
