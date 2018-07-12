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
        $subscription = $this->_get('subscription', 'ChargeBee_Subscription', 
        array('addons' => 'ChargeBee_SubscriptionAddon', 'coupons' => 'ChargeBee_SubscriptionCoupon', 'shipping_address' => 'ChargeBee_SubscriptionShippingAddress', 'referral_info' => 'ChargeBee_SubscriptionReferralInfo'));
        return $subscription;
    }

    function customer() 
    {
        $customer = $this->_get('customer', 'ChargeBee_Customer', 
        array('billing_address' => 'ChargeBee_CustomerBillingAddress', 'referral_urls' => 'ChargeBee_CustomerReferralUrl', 'contacts' => 'ChargeBee_CustomerContact', 'payment_method' => 'ChargeBee_CustomerPaymentMethod', 'balances' => 'ChargeBee_CustomerBalance'));
        return $customer;
    }

    function contact() 
    {
        $contact = $this->_get('contact', 'ChargeBee_Contact');
        return $contact;
    }

    function paymentSource() 
    {
        $payment_source = $this->_get('payment_source', 'ChargeBee_PaymentSource', 
        array('card' => 'ChargeBee_PaymentSourceCard', 'bank_account' => 'ChargeBee_PaymentSourceBankAccount', 'amazon_payment' => 'ChargeBee_PaymentSourceAmazonPayment', 'paypal' => 'ChargeBee_PaymentSourcePaypal'));
        return $payment_source;
    }

    function thirdPartyPaymentMethod() 
    {
        $third_party_payment_method = $this->_get('third_party_payment_method', 'ChargeBee_ThirdPartyPaymentMethod');
        return $third_party_payment_method;
    }

    function virtualBankAccount() 
    {
        $virtual_bank_account = $this->_get('virtual_bank_account', 'ChargeBee_VirtualBankAccount');
        return $virtual_bank_account;
    }

    function card() 
    {
        $card = $this->_get('card', 'ChargeBee_Card');
        return $card;
    }

    function promotionalCredit() 
    {
        $promotional_credit = $this->_get('promotional_credit', 'ChargeBee_PromotionalCredit');
        return $promotional_credit;
    }

    function invoice() 
    {
        $invoice = $this->_get('invoice', 'ChargeBee_Invoice', 
        array('line_items' => 'ChargeBee_InvoiceLineItem', 'discounts' => 'ChargeBee_InvoiceDiscount', 'line_item_discounts' => 'ChargeBee_InvoiceLineItemDiscount', 'taxes' => 'ChargeBee_InvoiceTax', 'line_item_taxes' => 'ChargeBee_InvoiceLineItemTax', 'linked_payments' => 'ChargeBee_InvoiceLinkedPayment', 'applied_credits' => 'ChargeBee_InvoiceAppliedCredit', 'adjustment_credit_notes' => 'ChargeBee_InvoiceAdjustmentCreditNote', 'issued_credit_notes' => 'ChargeBee_InvoiceIssuedCreditNote', 'linked_orders' => 'ChargeBee_InvoiceLinkedOrder', 'notes' => 'ChargeBee_InvoiceNote', 'shipping_address' => 'ChargeBee_InvoiceShippingAddress', 'billing_address' => 'ChargeBee_InvoiceBillingAddress'));
        return $invoice;
    }

    function creditNote() 
    {
        $credit_note = $this->_get('credit_note', 'ChargeBee_CreditNote', 
        array('line_items' => 'ChargeBee_CreditNoteLineItem', 'discounts' => 'ChargeBee_CreditNoteDiscount', 'line_item_discounts' => 'ChargeBee_CreditNoteLineItemDiscount', 'taxes' => 'ChargeBee_CreditNoteTax', 'line_item_taxes' => 'ChargeBee_CreditNoteLineItemTax', 'linked_refunds' => 'ChargeBee_CreditNoteLinkedRefund', 'allocations' => 'ChargeBee_CreditNoteAllocation'));
        return $credit_note;
    }

    function unbilledCharge() 
    {
        $unbilled_charge = $this->_get('unbilled_charge', 'ChargeBee_UnbilledCharge');
        return $unbilled_charge;
    }

    function order() 
    {
        $order = $this->_get('order', 'ChargeBee_Order');
        return $order;
    }

    function transaction() 
    {
        $transaction = $this->_get('transaction', 'ChargeBee_Transaction', 
        array('linked_invoices' => 'ChargeBee_TransactionLinkedInvoice', 'linked_credit_notes' => 'ChargeBee_TransactionLinkedCreditNote', 'linked_refunds' => 'ChargeBee_TransactionLinkedRefund'));
        return $transaction;
    }

    function hostedPage() 
    {
        $hosted_page = $this->_get('hosted_page', 'ChargeBee_HostedPage');
        return $hosted_page;
    }

    function estimate() 
    {
        $estimate = $this->_get('estimate', 'ChargeBee_Estimate', array(),
        array('subscription_estimate' => 'ChargeBee_SubscriptionEstimate', 'invoice_estimate' => 'ChargeBee_InvoiceEstimate', 'invoice_estimates' => 'ChargeBee_InvoiceEstimate', 'next_invoice_estimate' => 'ChargeBee_InvoiceEstimate', 'credit_note_estimates' => 'ChargeBee_CreditNoteEstimate', 'unbilled_charge_estimates' => 'ChargeBee_UnbilledCharge'));
        $estimate->_initDependant($this->_response['estimate'], 'subscription_estimate', 
        array('shipping_address' => 'ChargeBee_SubscriptionEstimateShippingAddress'));
        $estimate->_initDependant($this->_response['estimate'], 'invoice_estimate', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependant($this->_response['estimate'], 'next_invoice_estimate', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependantList($this->_response['estimate'], 'invoice_estimates', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependantList($this->_response['estimate'], 'credit_note_estimates', 
        array('line_items' => 'ChargeBee_CreditNoteEstimateLineItem', 'discounts' => 'ChargeBee_CreditNoteEstimateDiscount', 'taxes' => 'ChargeBee_CreditNoteEstimateTax', 'line_item_taxes' => 'ChargeBee_CreditNoteEstimateLineItemTax', 'line_item_discounts' => 'ChargeBee_CreditNoteEstimateLineItemDiscount'));
        $estimate->_initDependantList($this->_response['estimate'], 'unbilled_charge_estimates', 
        array());
        return $estimate;
    }

    function plan() 
    {
        $plan = $this->_get('plan', 'ChargeBee_Plan');
        return $plan;
    }

    function addon() 
    {
        $addon = $this->_get('addon', 'ChargeBee_Addon');
        return $addon;
    }

    function coupon() 
    {
        $coupon = $this->_get('coupon', 'ChargeBee_Coupon');
        return $coupon;
    }

    function couponSet() 
    {
        $coupon_set = $this->_get('coupon_set', 'ChargeBee_CouponSet');
        return $coupon_set;
    }

    function couponCode() 
    {
        $coupon_code = $this->_get('coupon_code', 'ChargeBee_CouponCode');
        return $coupon_code;
    }

    function address() 
    {
        $address = $this->_get('address', 'ChargeBee_Address');
        return $address;
    }

    function event() 
    {
        $event = $this->_get('event', 'ChargeBee_Event', 
        array('webhooks' => 'ChargeBee_EventWebhook'));
        return $event;
    }

    function comment() 
    {
        $comment = $this->_get('comment', 'ChargeBee_Comment');
        return $comment;
    }

    function download() 
    {
        $download = $this->_get('download', 'ChargeBee_Download');
        return $download;
    }

    function portalSession() 
    {
        $portal_session = $this->_get('portal_session', 'ChargeBee_PortalSession', 
        array('linked_customers' => 'ChargeBee_PortalSessionLinkedCustomer'));
        return $portal_session;
    }

    function siteMigrationDetail() 
    {
        $site_migration_detail = $this->_get('site_migration_detail', 'ChargeBee_SiteMigrationDetail');
        return $site_migration_detail;
    }

    function resourceMigration() 
    {
        $resource_migration = $this->_get('resource_migration', 'ChargeBee_ResourceMigration');
        return $resource_migration;
    }

    function timeMachine() 
    {
        $time_machine = $this->_get('time_machine', 'ChargeBee_TimeMachine');
        return $time_machine;
    }

    function export() 
    {
        $export = $this->_get('export', 'ChargeBee_Export', 
        array('download' => 'ChargeBee_ExportDownload'));
        return $export;
    }


    function unbilledCharges() 
    {
        $unbilled_charges = $this->_getList('unbilled_charges', 'ChargeBee_UnbilledCharge',
        array());
        return $unbilled_charges;
    }
    
    function creditNotes() 
    {
        $credit_notes = $this->_getList('credit_notes', 'ChargeBee_CreditNote',
        array('line_items' => 'ChargeBee_CreditNoteLineItem', 'discounts' => 'ChargeBee_CreditNoteDiscount', 'line_item_discounts' => 'ChargeBee_CreditNoteLineItemDiscount', 'taxes' => 'ChargeBee_CreditNoteTax', 'line_item_taxes' => 'ChargeBee_CreditNoteLineItemTax', 'linked_refunds' => 'ChargeBee_CreditNoteLinkedRefund', 'allocations' => 'ChargeBee_CreditNoteAllocation'));
        return $credit_notes;
    }
    
    function invoices() 
    {
        $invoices = $this->_getList('invoices', 'ChargeBee_Invoice',
        array('line_items' => 'ChargeBee_InvoiceLineItem', 'discounts' => 'ChargeBee_InvoiceDiscount', 'line_item_discounts' => 'ChargeBee_InvoiceLineItemDiscount', 'taxes' => 'ChargeBee_InvoiceTax', 'line_item_taxes' => 'ChargeBee_InvoiceLineItemTax', 'linked_payments' => 'ChargeBee_InvoiceLinkedPayment', 'applied_credits' => 'ChargeBee_InvoiceAppliedCredit', 'adjustment_credit_notes' => 'ChargeBee_InvoiceAdjustmentCreditNote', 'issued_credit_notes' => 'ChargeBee_InvoiceIssuedCreditNote', 'linked_orders' => 'ChargeBee_InvoiceLinkedOrder', 'notes' => 'ChargeBee_InvoiceNote', 'shipping_address' => 'ChargeBee_InvoiceShippingAddress', 'billing_address' => 'ChargeBee_InvoiceBillingAddress'));
        return $invoices;
    }
   
     public function toJson() {
           return json_encode($this->_response);
     } 
    
    private function _getList($type, $class, $subTypes = array(), $dependantTypes = array(),  $dependantSubTypes = array())
    {
        if(!array_key_exists($type, $this->_response))
        {
            return null;
        }
        if(!array_key_exists($type, $this->_responseObj))
        {
            $setVal = array();
            foreach($this->_response[$type] as $stV)
            {
                $obj = new $class($stV, $subTypes, $dependantTypes);
                foreach($dependantSubTypes as $k => $v)
                {
                    $obj->_initDependant($stV, $k, $v);
                }
                array_push($setVal, $obj);
            }
            $this->_responseObj[$type] = $setVal;
        }
        return $this->_responseObj[$type];        
    }
    
    private function _get($type, $class, $subTypes = array(), $dependantTypes = array())
    {
        if(!array_key_exists($type, $this->_response))
        {
                return null;
        }
        if(!array_key_exists($type, $this->_responseObj))
        {
                $this->_responseObj[$type] = new $class($this->_response[$type], $subTypes, $dependantTypes);
        }
        return $this->_responseObj[$type];
    }

}

?>