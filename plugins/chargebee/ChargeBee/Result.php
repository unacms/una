<?php

class ChargeBee_Result
{
    private $_response;
	
    private $_responseObj;

    function __construct($_response)
    {
            $this->_response = $_response;
            $this->_responseObj = array();
    }

    function subscription() 
    {
        return $this->_get('subscription', 'ChargeBee_Subscription', 
        array('addons' => 'ChargeBee_SubscriptionAddon', 'coupons' => 'ChargeBee_SubscriptionCoupon', 'shipping_address' => 'ChargeBee_SubscriptionShippingAddress'));
    }

    function customer() 
    {
        return $this->_get('customer', 'ChargeBee_Customer', 
        array('billing_address' => 'ChargeBee_CustomerBillingAddress', 'payment_method' => 'ChargeBee_CustomerPaymentMethod'));
    }

    function card() 
    {
        return $this->_get('card', 'ChargeBee_Card');
    }

    function invoice() 
    {
        return $this->_get('invoice', 'ChargeBee_Invoice', 
        array('line_items' => 'ChargeBee_InvoiceLineItem', 'discounts' => 'ChargeBee_InvoiceDiscount', 'taxes' => 'ChargeBee_InvoiceTax', 'invoice_transactions' => 'ChargeBee_InvoiceLinkedTransaction', 'orders' => 'ChargeBee_InvoiceLinkedOrder', 'invoice_notes' => 'ChargeBee_InvoiceNote', 'shipping_address' => 'ChargeBee_InvoiceShippingAddress', 'billing_address' => 'ChargeBee_InvoiceBillingAddress'));
    }

    function order() 
    {
        return $this->_get('order', 'ChargeBee_Order');
    }

    function transaction() 
    {
        return $this->_get('transaction', 'ChargeBee_Transaction', 
        array('invoice_transactions' => 'ChargeBee_TransactionLinkedInvoice'));
    }

    function hostedPage() 
    {
        return $this->_get('hosted_page', 'ChargeBee_HostedPage');
    }

    function estimate() 
    {
        return $this->_get('estimate', 'ChargeBee_Estimate', 
        array('line_items' => 'ChargeBee_EstimateLineItem', 'discounts' => 'ChargeBee_EstimateDiscount', 'taxes' => 'ChargeBee_EstimateTax'));
    }

    function plan() 
    {
        return $this->_get('plan', 'ChargeBee_Plan');
    }

    function addon() 
    {
        return $this->_get('addon', 'ChargeBee_Addon');
    }

    function coupon() 
    {
        return $this->_get('coupon', 'ChargeBee_Coupon');
    }

    function couponCode() 
    {
        return $this->_get('coupon_code', 'ChargeBee_CouponCode');
    }

    function address() 
    {
        return $this->_get('address', 'ChargeBee_Address');
    }

    function event() 
    {
        return $this->_get('event', 'ChargeBee_Event', 
        array('webhooks' => 'ChargeBee_EventWebhook'));
    }

    function comment() 
    {
        return $this->_get('comment', 'ChargeBee_Comment');
    }

    function download() 
    {
        return $this->_get('download', 'ChargeBee_Download');
    }

    function portalSession() 
    {
        return $this->_get('portal_session', 'ChargeBee_PortalSession', 
        array('linked_customers' => 'ChargeBee_PortalSessionLinkedCustomer'));
    }


    private function _get($type, $class, $subTypes = array())
    {
        if(!array_key_exists($type, $this->_response))
        {
                return null;
        }
        if(!array_key_exists($type, $this->_responseObj))
        {
                $this->_responseObj[$type] = new $class($this->_response[$type], $subTypes);
        }
        return $this->_responseObj[$type];
    }

}

?>