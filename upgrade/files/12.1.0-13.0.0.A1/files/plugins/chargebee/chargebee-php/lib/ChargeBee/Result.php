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
        array('subscription_items' => 'ChargeBee_SubscriptionSubscriptionItem', 'item_tiers' => 'ChargeBee_SubscriptionItemTier', 'charged_items' => 'ChargeBee_SubscriptionChargedItem', 'addons' => 'ChargeBee_SubscriptionAddon', 'event_based_addons' => 'ChargeBee_SubscriptionEventBasedAddon', 'charged_event_based_addons' => 'ChargeBee_SubscriptionChargedEventBasedAddon', 'coupons' => 'ChargeBee_SubscriptionCoupon', 'shipping_address' => 'ChargeBee_SubscriptionShippingAddress', 'referral_info' => 'ChargeBee_SubscriptionReferralInfo', 'contract_term' => 'ChargeBee_SubscriptionContractTerm'));
        return $subscription;
    }

    function contractTerm() 
    {
        $contract_term = $this->_get('contract_term', 'ChargeBee_ContractTerm');
        return $contract_term;
    }

    function advanceInvoiceSchedule() 
    {
        $advance_invoice_schedule = $this->_get('advance_invoice_schedule', 'ChargeBee_AdvanceInvoiceSchedule', 
        array('fixed_interval_schedule' => 'ChargeBee_AdvanceInvoiceScheduleFixedIntervalSchedule', 'specific_dates_schedule' => 'ChargeBee_AdvanceInvoiceScheduleSpecificDatesSchedule'));
        return $advance_invoice_schedule;
    }

    function customer() 
    {
        $customer = $this->_get('customer', 'ChargeBee_Customer', 
        array('billing_address' => 'ChargeBee_CustomerBillingAddress', 'referral_urls' => 'ChargeBee_CustomerReferralUrl', 'contacts' => 'ChargeBee_CustomerContact', 'payment_method' => 'ChargeBee_CustomerPaymentMethod', 'balances' => 'ChargeBee_CustomerBalance', 'relationship' => 'ChargeBee_CustomerRelationship', 'parent_account_access' => 'ChargeBee_CustomerParentAccountAccess', 'child_account_access' => 'ChargeBee_CustomerChildAccountAccess'));
        return $customer;
    }

    function hierarchy() 
    {
        $hierarchy = $this->_get('hierarchy', 'ChargeBee_Hierarchy');
        return $hierarchy;
    }

    function contact() 
    {
        $contact = $this->_get('contact', 'ChargeBee_Contact');
        return $contact;
    }

    function token() 
    {
        $token = $this->_get('token', 'ChargeBee_Token');
        return $token;
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
        array('line_items' => 'ChargeBee_InvoiceLineItem', 'discounts' => 'ChargeBee_InvoiceDiscount', 'line_item_discounts' => 'ChargeBee_InvoiceLineItemDiscount', 'taxes' => 'ChargeBee_InvoiceTax', 'line_item_taxes' => 'ChargeBee_InvoiceLineItemTax', 'line_item_tiers' => 'ChargeBee_InvoiceLineItemTier', 'linked_payments' => 'ChargeBee_InvoiceLinkedPayment', 'dunning_attempts' => 'ChargeBee_InvoiceDunningAttempt', 'applied_credits' => 'ChargeBee_InvoiceAppliedCredit', 'adjustment_credit_notes' => 'ChargeBee_InvoiceAdjustmentCreditNote', 'issued_credit_notes' => 'ChargeBee_InvoiceIssuedCreditNote', 'linked_orders' => 'ChargeBee_InvoiceLinkedOrder', 'notes' => 'ChargeBee_InvoiceNote', 'shipping_address' => 'ChargeBee_InvoiceShippingAddress', 'billing_address' => 'ChargeBee_InvoiceBillingAddress'));
        return $invoice;
    }

    function creditNote() 
    {
        $credit_note = $this->_get('credit_note', 'ChargeBee_CreditNote', 
        array('line_items' => 'ChargeBee_CreditNoteLineItem', 'discounts' => 'ChargeBee_CreditNoteDiscount', 'line_item_discounts' => 'ChargeBee_CreditNoteLineItemDiscount', 'line_item_tiers' => 'ChargeBee_CreditNoteLineItemTier', 'taxes' => 'ChargeBee_CreditNoteTax', 'line_item_taxes' => 'ChargeBee_CreditNoteLineItemTax', 'linked_refunds' => 'ChargeBee_CreditNoteLinkedRefund', 'allocations' => 'ChargeBee_CreditNoteAllocation'));
        return $credit_note;
    }

    function unbilledCharge() 
    {
        $unbilled_charge = $this->_get('unbilled_charge', 'ChargeBee_UnbilledCharge', 
        array('tiers' => 'ChargeBee_UnbilledChargeTier'));
        return $unbilled_charge;
    }

    function order() 
    {
        $order = $this->_get('order', 'ChargeBee_Order', 
        array('order_line_items' => 'ChargeBee_OrderOrderLineItem', 'shipping_address' => 'ChargeBee_OrderShippingAddress', 'billing_address' => 'ChargeBee_OrderBillingAddress', 'line_item_taxes' => 'ChargeBee_OrderLineItemTax', 'line_item_discounts' => 'ChargeBee_OrderLineItemDiscount', 'linked_credit_notes' => 'ChargeBee_OrderLinkedCreditNote', 'resent_orders' => 'ChargeBee_OrderResentOrder'));
        return $order;
    }

    function gift() 
    {
        $gift = $this->_get('gift', 'ChargeBee_Gift', 
        array('gifter' => 'ChargeBee_GiftGifter', 'gift_receiver' => 'ChargeBee_GiftGiftReceiver', 'gift_timelines' => 'ChargeBee_GiftGiftTimeline'));
        return $gift;
    }

    function transaction() 
    {
        $transaction = $this->_get('transaction', 'ChargeBee_Transaction', 
        array('linked_invoices' => 'ChargeBee_TransactionLinkedInvoice', 'linked_credit_notes' => 'ChargeBee_TransactionLinkedCreditNote', 'linked_refunds' => 'ChargeBee_TransactionLinkedRefund', 'linked_payments' => 'ChargeBee_TransactionLinkedPayment'));
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
        array('shipping_address' => 'ChargeBee_SubscriptionEstimateShippingAddress', 'contract_term' => 'ChargeBee_SubscriptionEstimateContractTerm'));
        $estimate->_initDependant($this->_response['estimate'], 'invoice_estimate', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_tiers' => 'ChargeBee_InvoiceEstimateLineItemTier', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependant($this->_response['estimate'], 'next_invoice_estimate', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_tiers' => 'ChargeBee_InvoiceEstimateLineItemTier', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependantList($this->_response['estimate'], 'invoice_estimates', 
        array('line_items' => 'ChargeBee_InvoiceEstimateLineItem', 'discounts' => 'ChargeBee_InvoiceEstimateDiscount', 'taxes' => 'ChargeBee_InvoiceEstimateTax', 'line_item_taxes' => 'ChargeBee_InvoiceEstimateLineItemTax', 'line_item_tiers' => 'ChargeBee_InvoiceEstimateLineItemTier', 'line_item_discounts' => 'ChargeBee_InvoiceEstimateLineItemDiscount'));
        $estimate->_initDependantList($this->_response['estimate'], 'credit_note_estimates', 
        array('line_items' => 'ChargeBee_CreditNoteEstimateLineItem', 'discounts' => 'ChargeBee_CreditNoteEstimateDiscount', 'taxes' => 'ChargeBee_CreditNoteEstimateTax', 'line_item_taxes' => 'ChargeBee_CreditNoteEstimateLineItemTax', 'line_item_discounts' => 'ChargeBee_CreditNoteEstimateLineItemDiscount', 'line_item_tiers' => 'ChargeBee_CreditNoteEstimateLineItemTier'));
        $estimate->_initDependantList($this->_response['estimate'], 'unbilled_charge_estimates', 
        array('tiers' => 'ChargeBee_UnbilledChargeTier'));
        return $estimate;
    }

    function quote() 
    {
        $quote = $this->_get('quote', 'ChargeBee_Quote', 
        array('line_items' => 'ChargeBee_QuoteLineItem', 'discounts' => 'ChargeBee_QuoteDiscount', 'line_item_discounts' => 'ChargeBee_QuoteLineItemDiscount', 'taxes' => 'ChargeBee_QuoteTax', 'line_item_taxes' => 'ChargeBee_QuoteLineItemTax', 'line_item_tiers' => 'ChargeBee_QuoteLineItemTier', 'shipping_address' => 'ChargeBee_QuoteShippingAddress', 'billing_address' => 'ChargeBee_QuoteBillingAddress'));
        return $quote;
    }

    function quotedSubscription() 
    {
        $quoted_subscription = $this->_get('quoted_subscription', 'ChargeBee_QuotedSubscription', 
        array('addons' => 'ChargeBee_QuotedSubscriptionAddon', 'event_based_addons' => 'ChargeBee_QuotedSubscriptionEventBasedAddon', 'coupons' => 'ChargeBee_QuotedSubscriptionCoupon', 'discounts' => 'ChargeBee_QuotedSubscriptionDiscount', 'subscription_items' => 'ChargeBee_QuotedSubscriptionSubscriptionItem', 'item_tiers' => 'ChargeBee_QuotedSubscriptionItemTier', 'quoted_contract_term' => 'ChargeBee_QuotedSubscriptionQuotedContractTerm'));
        return $quoted_subscription;
    }

    function quoteLineGroup() 
    {
        $quote_line_group = $this->_get('quote_line_group', 'ChargeBee_QuoteLineGroup', 
        array('line_items' => 'ChargeBee_QuoteLineGroupLineItem', 'discounts' => 'ChargeBee_QuoteLineGroupDiscount', 'line_item_discounts' => 'ChargeBee_QuoteLineGroupLineItemDiscount', 'taxes' => 'ChargeBee_QuoteLineGroupTax', 'line_item_taxes' => 'ChargeBee_QuoteLineGroupLineItemTax'));
        return $quote_line_group;
    }

    function plan() 
    {
        $plan = $this->_get('plan', 'ChargeBee_Plan', 
        array('tiers' => 'ChargeBee_PlanTier', 'applicable_addons' => 'ChargeBee_PlanApplicableAddon', 'attached_addons' => 'ChargeBee_PlanAttachedAddon', 'event_based_addons' => 'ChargeBee_PlanEventBasedAddon'));
        return $plan;
    }

    function addon() 
    {
        $addon = $this->_get('addon', 'ChargeBee_Addon', 
        array('tiers' => 'ChargeBee_AddonTier'));
        return $addon;
    }

    function coupon() 
    {
        $coupon = $this->_get('coupon', 'ChargeBee_Coupon', 
        array('item_constraints' => 'ChargeBee_CouponItemConstraint', 'item_constraint_criteria' => 'ChargeBee_CouponItemConstraintCriteria'));
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

    function usage() 
    {
        $usage = $this->_get('usage', 'ChargeBee_Usage');
        return $usage;
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

    function paymentIntent() 
    {
        $payment_intent = $this->_get('payment_intent', 'ChargeBee_PaymentIntent', 
        array('payment_attempt' => 'ChargeBee_PaymentIntentPaymentAttempt'));
        return $payment_intent;
    }

    function itemFamily()
    {
        $item_family = $this->_get('item_family', 'ChargeBee_ItemFamily');
        return $item_family;
    }

    function item() 
    {
        $item = $this->_get('item', 'ChargeBee_Item', 
        array('applicable_items' => 'ChargeBee_ItemApplicableItem'));
        return $item;
    }

    function itemPrice() 
    {
        $item_price = $this->_get('item_price', 'ChargeBee_ItemPrice', 
        array('tiers' => 'ChargeBee_ItemPriceTier', 'tax_detail' => 'ChargeBee_ItemPriceTaxDetail', 'accounting_detail' => 'ChargeBee_ItemPriceAccountingDetail'));
        return $item_price;
    }

    function attachedItem()
    {
        $attached_item = $this->_get('attached_item', 'ChargeBee_AttachedItem');
        return $attached_item;
    }

    function differentialPrice() 
    {
        $differential_price = $this->_get('differential_price', 'ChargeBee_DifferentialPrice', 
        array('tiers' => 'ChargeBee_DifferentialPriceTier', 'parent_periods' => 'ChargeBee_DifferentialPriceParentPeriod'));
        return $differential_price;
    }

    function unbilledCharges() 
    {
        $unbilled_charges = $this->_getList('unbilled_charges', 'ChargeBee_UnbilledCharge',
        array('tiers' => 'ChargeBee_UnbilledChargeTier'));
        return $unbilled_charges;
    }
    
    function creditNotes() 
    {
        $credit_notes = $this->_getList('credit_notes', 'ChargeBee_CreditNote',
        array('line_items' => 'ChargeBee_CreditNoteLineItem', 'discounts' => 'ChargeBee_CreditNoteDiscount', 'line_item_discounts' => 'ChargeBee_CreditNoteLineItemDiscount', 'line_item_tiers' => 'ChargeBee_CreditNoteLineItemTier', 'taxes' => 'ChargeBee_CreditNoteTax', 'line_item_taxes' => 'ChargeBee_CreditNoteLineItemTax', 'linked_refunds' => 'ChargeBee_CreditNoteLinkedRefund', 'allocations' => 'ChargeBee_CreditNoteAllocation'));
        return $credit_notes;
    }
    
    function advanceInvoiceSchedules() 
    {
        $advance_invoice_schedules = $this->_getList('advance_invoice_schedules', 'ChargeBee_AdvanceInvoiceSchedule',
        array('fixed_interval_schedule' => 'ChargeBee_AdvanceInvoiceScheduleFixedIntervalSchedule', 'specific_dates_schedule' => 'ChargeBee_AdvanceInvoiceScheduleSpecificDatesSchedule'));
        return $advance_invoice_schedules;
    }
    
    function hierarchies() 
    {
        $hierarchies = $this->_getList('hierarchies', 'ChargeBee_Hierarchy',
        array());
        return $hierarchies;
    }
    
    function invoices() 
    {
        $invoices = $this->_getList('invoices', 'ChargeBee_Invoice',
        array('line_items' => 'ChargeBee_InvoiceLineItem', 'discounts' => 'ChargeBee_InvoiceDiscount', 'line_item_discounts' => 'ChargeBee_InvoiceLineItemDiscount', 'taxes' => 'ChargeBee_InvoiceTax', 'line_item_taxes' => 'ChargeBee_InvoiceLineItemTax', 'line_item_tiers' => 'ChargeBee_InvoiceLineItemTier', 'linked_payments' => 'ChargeBee_InvoiceLinkedPayment', 'dunning_attempts' => 'ChargeBee_InvoiceDunningAttempt', 'applied_credits' => 'ChargeBee_InvoiceAppliedCredit', 'adjustment_credit_notes' => 'ChargeBee_InvoiceAdjustmentCreditNote', 'issued_credit_notes' => 'ChargeBee_InvoiceIssuedCreditNote', 'linked_orders' => 'ChargeBee_InvoiceLinkedOrder', 'notes' => 'ChargeBee_InvoiceNote', 'shipping_address' => 'ChargeBee_InvoiceShippingAddress', 'billing_address' => 'ChargeBee_InvoiceBillingAddress'));
        return $invoices;
    }
    
    function differentialPrices() 
    {
        $differential_prices = $this->_getList('differential_prices', 'ChargeBee_DifferentialPrice',
        array('tiers' => 'ChargeBee_DifferentialPriceTier', 'parent_periods' => 'ChargeBee_DifferentialPriceParentPeriod'));
        return $differential_prices;
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