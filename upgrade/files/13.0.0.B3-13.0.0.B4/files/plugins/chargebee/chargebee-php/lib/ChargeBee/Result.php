<?php

namespace ChargeBee\ChargeBee;

use ChargeBee\ChargeBee\Models;

class Result
{
    private $_response;
	
    private $_responseObj;

    public function __construct($_response)
    {
            $this->_response = $_response;
            $this->_responseObj = array();
    }

    public function subscription() 
    {
        $subscription = $this->_get('subscription', Models\Subscription::class, 
        array( 
			'subscription_items' => Models\SubscriptionSubscriptionItem::class, 
			'item_tiers' => Models\SubscriptionItemTier::class, 
			'charged_items' => Models\SubscriptionChargedItem::class, 
			'addons' => Models\SubscriptionAddon::class, 
			'event_based_addons' => Models\SubscriptionEventBasedAddon::class, 
			'charged_event_based_addons' => Models\SubscriptionChargedEventBasedAddon::class, 
			'coupons' => Models\SubscriptionCoupon::class, 
			'shipping_address' => Models\SubscriptionShippingAddress::class, 
			'referral_info' => Models\SubscriptionReferralInfo::class, 
			'contract_term' => Models\SubscriptionContractTerm::class, 
			'discounts' => Models\SubscriptionDiscount::class
		));
        return $subscription;
    }

    public function contractTerm() 
    {
        $contract_term = $this->_get('contract_term', Models\ContractTerm::class);
        return $contract_term;
    }

    public function discount() 
    {
        $discount = $this->_get('discount', Models\Discount::class);
        return $discount;
    }

    public function advanceInvoiceSchedule() 
    {
        $advance_invoice_schedule = $this->_get('advance_invoice_schedule', Models\AdvanceInvoiceSchedule::class, 
        array( 
			'fixed_interval_schedule' => Models\AdvanceInvoiceScheduleFixedIntervalSchedule::class, 
			'specific_dates_schedule' => Models\AdvanceInvoiceScheduleSpecificDatesSchedule::class
		));
        return $advance_invoice_schedule;
    }

    public function customer() 
    {
        $customer = $this->_get('customer', Models\Customer::class, 
        array( 
			'billing_address' => Models\CustomerBillingAddress::class, 
			'referral_urls' => Models\CustomerReferralUrl::class, 
			'contacts' => Models\CustomerContact::class, 
			'payment_method' => Models\CustomerPaymentMethod::class, 
			'balances' => Models\CustomerBalance::class, 
			'entity_identifiers' => Models\CustomerEntityIdentifier::class, 
			'relationship' => Models\CustomerRelationship::class, 
			'parent_account_access' => Models\CustomerParentAccountAccess::class, 
			'child_account_access' => Models\CustomerChildAccountAccess::class
		));
        return $customer;
    }

    public function hierarchy() 
    {
        $hierarchy = $this->_get('hierarchy', Models\Hierarchy::class);
        return $hierarchy;
    }

    public function contact() 
    {
        $contact = $this->_get('contact', Models\Contact::class);
        return $contact;
    }

    public function token() 
    {
        $token = $this->_get('token', Models\Token::class);
        return $token;
    }

    public function paymentSource() 
    {
        $payment_source = $this->_get('payment_source', Models\PaymentSource::class, 
        array( 
			'card' => Models\PaymentSourceCard::class, 
			'bank_account' => Models\PaymentSourceBankAccount::class, 
			'amazon_payment' => Models\PaymentSourceAmazonPayment::class, 
			'upi' => Models\PaymentSourceUpi::class, 
			'paypal' => Models\PaymentSourcePaypal::class, 
			'mandates' => Models\PaymentSourceMandate::class
		));
        return $payment_source;
    }

    public function thirdPartyPaymentMethod() 
    {
        $third_party_payment_method = $this->_get('third_party_payment_method', Models\ThirdPartyPaymentMethod::class);
        return $third_party_payment_method;
    }

    public function virtualBankAccount() 
    {
        $virtual_bank_account = $this->_get('virtual_bank_account', Models\VirtualBankAccount::class);
        return $virtual_bank_account;
    }

    public function card() 
    {
        $card = $this->_get('card', Models\Card::class);
        return $card;
    }

    public function promotionalCredit() 
    {
        $promotional_credit = $this->_get('promotional_credit', Models\PromotionalCredit::class);
        return $promotional_credit;
    }

    public function invoice() 
    {
        $invoice = $this->_get('invoice', Models\Invoice::class, 
        array( 
			'line_items' => Models\InvoiceLineItem::class, 
			'discounts' => Models\InvoiceDiscount::class, 
			'line_item_discounts' => Models\InvoiceLineItemDiscount::class, 
			'taxes' => Models\InvoiceTax::class, 
			'line_item_taxes' => Models\InvoiceLineItemTax::class, 
			'line_item_tiers' => Models\InvoiceLineItemTier::class, 
			'linked_payments' => Models\InvoiceLinkedPayment::class, 
			'dunning_attempts' => Models\InvoiceDunningAttempt::class, 
			'applied_credits' => Models\InvoiceAppliedCredit::class, 
			'adjustment_credit_notes' => Models\InvoiceAdjustmentCreditNote::class, 
			'issued_credit_notes' => Models\InvoiceIssuedCreditNote::class, 
			'linked_orders' => Models\InvoiceLinkedOrder::class, 
			'notes' => Models\InvoiceNote::class, 
			'shipping_address' => Models\InvoiceShippingAddress::class, 
			'billing_address' => Models\InvoiceBillingAddress::class, 
			'einvoice' => Models\InvoiceEinvoice::class
		));
        return $invoice;
    }

    public function taxWithheld() 
    {
        $tax_withheld = $this->_get('tax_withheld', Models\TaxWithheld::class);
        return $tax_withheld;
    }

    public function creditNote() 
    {
        $credit_note = $this->_get('credit_note', Models\CreditNote::class, 
        array( 
			'einvoice' => Models\CreditNoteEinvoice::class, 
			'line_items' => Models\CreditNoteLineItem::class, 
			'discounts' => Models\CreditNoteDiscount::class, 
			'line_item_discounts' => Models\CreditNoteLineItemDiscount::class, 
			'line_item_tiers' => Models\CreditNoteLineItemTier::class, 
			'taxes' => Models\CreditNoteTax::class, 
			'line_item_taxes' => Models\CreditNoteLineItemTax::class, 
			'linked_refunds' => Models\CreditNoteLinkedRefund::class, 
			'allocations' => Models\CreditNoteAllocation::class
		));
        return $credit_note;
    }

    public function unbilledCharge() 
    {
        $unbilled_charge = $this->_get('unbilled_charge', Models\UnbilledCharge::class, 
        array( 
			'tiers' => Models\UnbilledChargeTier::class
		));
        return $unbilled_charge;
    }

    public function order() 
    {
        $order = $this->_get('order', Models\Order::class, 
        array( 
			'order_line_items' => Models\OrderOrderLineItem::class, 
			'shipping_address' => Models\OrderShippingAddress::class, 
			'billing_address' => Models\OrderBillingAddress::class, 
			'line_item_taxes' => Models\OrderLineItemTax::class, 
			'line_item_discounts' => Models\OrderLineItemDiscount::class, 
			'linked_credit_notes' => Models\OrderLinkedCreditNote::class, 
			'resent_orders' => Models\OrderResentOrder::class
		));
        return $order;
    }

    public function gift() 
    {
        $gift = $this->_get('gift', Models\Gift::class, 
        array( 
			'gifter' => Models\GiftGifter::class, 
			'gift_receiver' => Models\GiftGiftReceiver::class, 
			'gift_timelines' => Models\GiftGiftTimeline::class
		));
        return $gift;
    }

    public function transaction() 
    {
        $transaction = $this->_get('transaction', Models\Transaction::class, 
        array( 
			'linked_invoices' => Models\TransactionLinkedInvoice::class, 
			'linked_credit_notes' => Models\TransactionLinkedCreditNote::class, 
			'linked_refunds' => Models\TransactionLinkedRefund::class, 
			'linked_payments' => Models\TransactionLinkedPayment::class
		));
        return $transaction;
    }

    public function hostedPage() 
    {
        $hosted_page = $this->_get('hosted_page', Models\HostedPage::class);
        return $hosted_page;
    }

    public function estimate() 
    {
        $estimate = $this->_get('estimate', Models\Estimate::class, array(),
        array( 
			'subscription_estimate' => Models\SubscriptionEstimate::class, 
			'subscription_estimates' => Models\SubscriptionEstimate::class, 
			'invoice_estimate' => Models\InvoiceEstimate::class, 
			'invoice_estimates' => Models\InvoiceEstimate::class, 
			'next_invoice_estimate' => Models\InvoiceEstimate::class, 
			'credit_note_estimates' => Models\CreditNoteEstimate::class, 
			'unbilled_charge_estimates' => Models\UnbilledCharge::class
		));
        $estimate->_initDependant($this->_response['estimate'], 'subscription_estimate', 
        array( 
			'shipping_address' => Models\SubscriptionEstimateShippingAddress::class, 
			'contract_term' => Models\SubscriptionEstimateContractTerm::class
		));
        $estimate->_initDependant($this->_response['estimate'], 'invoice_estimate', 
        array( 
			'line_items' => Models\InvoiceEstimateLineItem::class, 
			'discounts' => Models\InvoiceEstimateDiscount::class, 
			'taxes' => Models\InvoiceEstimateTax::class, 
			'line_item_taxes' => Models\InvoiceEstimateLineItemTax::class, 
			'line_item_tiers' => Models\InvoiceEstimateLineItemTier::class, 
			'line_item_discounts' => Models\InvoiceEstimateLineItemDiscount::class
		));
        $estimate->_initDependant($this->_response['estimate'], 'next_invoice_estimate', 
        array( 
			'line_items' => Models\InvoiceEstimateLineItem::class, 
			'discounts' => Models\InvoiceEstimateDiscount::class, 
			'taxes' => Models\InvoiceEstimateTax::class, 
			'line_item_taxes' => Models\InvoiceEstimateLineItemTax::class, 
			'line_item_tiers' => Models\InvoiceEstimateLineItemTier::class, 
			'line_item_discounts' => Models\InvoiceEstimateLineItemDiscount::class
		));
        $estimate->_initDependantList($this->_response['estimate'], 'subscription_estimates', 
        array( 
			'shipping_address' => Models\SubscriptionEstimateShippingAddress::class, 
			'contract_term' => Models\SubscriptionEstimateContractTerm::class
		));
        $estimate->_initDependantList($this->_response['estimate'], 'invoice_estimates', 
        array( 
			'line_items' => Models\InvoiceEstimateLineItem::class, 
			'discounts' => Models\InvoiceEstimateDiscount::class, 
			'taxes' => Models\InvoiceEstimateTax::class, 
			'line_item_taxes' => Models\InvoiceEstimateLineItemTax::class, 
			'line_item_tiers' => Models\InvoiceEstimateLineItemTier::class, 
			'line_item_discounts' => Models\InvoiceEstimateLineItemDiscount::class
		));
        $estimate->_initDependantList($this->_response['estimate'], 'credit_note_estimates', 
        array( 
			'line_items' => Models\CreditNoteEstimateLineItem::class, 
			'discounts' => Models\CreditNoteEstimateDiscount::class, 
			'taxes' => Models\CreditNoteEstimateTax::class, 
			'line_item_taxes' => Models\CreditNoteEstimateLineItemTax::class, 
			'line_item_discounts' => Models\CreditNoteEstimateLineItemDiscount::class, 
			'line_item_tiers' => Models\CreditNoteEstimateLineItemTier::class
		));
        $estimate->_initDependantList($this->_response['estimate'], 'unbilled_charge_estimates', 
        array( 
			'tiers' => Models\UnbilledChargeTier::class
		));
        return $estimate;
    }

    public function quote() 
    {
        $quote = $this->_get('quote', Models\Quote::class, 
        array( 
			'line_items' => Models\QuoteLineItem::class, 
			'discounts' => Models\QuoteDiscount::class, 
			'line_item_discounts' => Models\QuoteLineItemDiscount::class, 
			'taxes' => Models\QuoteTax::class, 
			'line_item_taxes' => Models\QuoteLineItemTax::class, 
			'line_item_tiers' => Models\QuoteLineItemTier::class, 
			'shipping_address' => Models\QuoteShippingAddress::class, 
			'billing_address' => Models\QuoteBillingAddress::class
		));
        return $quote;
    }

    public function quotedSubscription() 
    {
        $quoted_subscription = $this->_get('quoted_subscription', Models\QuotedSubscription::class, 
        array( 
			'addons' => Models\QuotedSubscriptionAddon::class, 
			'event_based_addons' => Models\QuotedSubscriptionEventBasedAddon::class, 
			'coupons' => Models\QuotedSubscriptionCoupon::class, 
			'subscription_items' => Models\QuotedSubscriptionSubscriptionItem::class, 
			'item_tiers' => Models\QuotedSubscriptionItemTier::class, 
			'quoted_contract_term' => Models\QuotedSubscriptionQuotedContractTerm::class
		));
        return $quoted_subscription;
    }

    public function quotedCharge() 
    {
        $quoted_charge = $this->_get('quoted_charge', Models\QuotedCharge::class, 
        array( 
			'charges' => Models\QuotedChargeCharge::class, 
			'addons' => Models\QuotedChargeAddon::class, 
			'invoice_items' => Models\QuotedChargeInvoiceItem::class, 
			'item_tiers' => Models\QuotedChargeItemTier::class, 
			'coupons' => Models\QuotedChargeCoupon::class
		));
        return $quoted_charge;
    }

    public function quoteLineGroup() 
    {
        $quote_line_group = $this->_get('quote_line_group', Models\QuoteLineGroup::class, 
        array( 
			'line_items' => Models\QuoteLineGroupLineItem::class, 
			'discounts' => Models\QuoteLineGroupDiscount::class, 
			'line_item_discounts' => Models\QuoteLineGroupLineItemDiscount::class, 
			'taxes' => Models\QuoteLineGroupTax::class, 
			'line_item_taxes' => Models\QuoteLineGroupLineItemTax::class
		));
        return $quote_line_group;
    }

    public function plan() 
    {
        $plan = $this->_get('plan', Models\Plan::class, 
        array( 
			'tiers' => Models\PlanTier::class, 
			'applicable_addons' => Models\PlanApplicableAddon::class, 
			'attached_addons' => Models\PlanAttachedAddon::class, 
			'event_based_addons' => Models\PlanEventBasedAddon::class
		));
        return $plan;
    }

    public function addon() 
    {
        $addon = $this->_get('addon', Models\Addon::class, 
        array( 
			'tiers' => Models\AddonTier::class
		));
        return $addon;
    }

    public function coupon() 
    {
        $coupon = $this->_get('coupon', Models\Coupon::class, 
        array( 
			'item_constraints' => Models\CouponItemConstraint::class, 
			'item_constraint_criteria' => Models\CouponItemConstraintCriteria::class
		));
        return $coupon;
    }

    public function couponSet() 
    {
        $coupon_set = $this->_get('coupon_set', Models\CouponSet::class);
        return $coupon_set;
    }

    public function couponCode() 
    {
        $coupon_code = $this->_get('coupon_code', Models\CouponCode::class);
        return $coupon_code;
    }

    public function address() 
    {
        $address = $this->_get('address', Models\Address::class);
        return $address;
    }

    public function usage() 
    {
        $usage = $this->_get('usage', Models\Usage::class);
        return $usage;
    }

    public function event() 
    {
        $event = $this->_get('event', Models\Event::class, 
        array( 
			'webhooks' => Models\EventWebhook::class
		));
        return $event;
    }

    public function comment() 
    {
        $comment = $this->_get('comment', Models\Comment::class);
        return $comment;
    }

    public function download() 
    {
        $download = $this->_get('download', Models\Download::class);
        return $download;
    }

    public function portalSession() 
    {
        $portal_session = $this->_get('portal_session', Models\PortalSession::class, 
        array( 
			'linked_customers' => Models\PortalSessionLinkedCustomer::class
		));
        return $portal_session;
    }

    public function siteMigrationDetail() 
    {
        $site_migration_detail = $this->_get('site_migration_detail', Models\SiteMigrationDetail::class);
        return $site_migration_detail;
    }

    public function resourceMigration() 
    {
        $resource_migration = $this->_get('resource_migration', Models\ResourceMigration::class);
        return $resource_migration;
    }

    public function timeMachine() 
    {
        $time_machine = $this->_get('time_machine', Models\TimeMachine::class);
        return $time_machine;
    }

    public function export() 
    {
        $export = $this->_get('export', Models\Export::class, 
        array( 
			'download' => Models\ExportDownload::class
		));
        return $export;
    }

    public function paymentIntent() 
    {
        $payment_intent = $this->_get('payment_intent', Models\PaymentIntent::class, 
        array( 
			'payment_attempt' => Models\PaymentIntentPaymentAttempt::class
		));
        return $payment_intent;
    }

    public function itemFamily() 
    {
        $item_family = $this->_get('item_family', Models\ItemFamily::class);
        return $item_family;
    }

    public function item() 
    {
        $item = $this->_get('item', Models\Item::class, 
        array( 
			'applicable_items' => Models\ItemApplicableItem::class
		));
        return $item;
    }

    public function itemPrice() 
    {
        $item_price = $this->_get('item_price', Models\ItemPrice::class, 
        array( 
			'tiers' => Models\ItemPriceTier::class, 
			'tax_detail' => Models\ItemPriceTaxDetail::class, 
			'accounting_detail' => Models\ItemPriceAccountingDetail::class
		));
        return $item_price;
    }

    public function attachedItem() 
    {
        $attached_item = $this->_get('attached_item', Models\AttachedItem::class);
        return $attached_item;
    }

    public function differentialPrice() 
    {
        $differential_price = $this->_get('differential_price', Models\DifferentialPrice::class, 
        array( 
			'tiers' => Models\DifferentialPriceTier::class, 
			'parent_periods' => Models\DifferentialPriceParentPeriod::class
		));
        return $differential_price;
    }

    public function feature() 
    {
        $feature = $this->_get('feature', Models\Feature::class, 
        array( 
			'levels' => Models\FeatureLevel::class
		));
        return $feature;
    }

    public function impactedSubscription() 
    {
        $impacted_subscription = $this->_get('impacted_subscription', Models\ImpactedSubscription::class, 
        array( 
			'download' => Models\ImpactedSubscriptionDownload::class
		));
        return $impacted_subscription;
    }

    public function impactedItem() 
    {
        $impacted_item = $this->_get('impacted_item', Models\ImpactedItem::class, 
        array( 
			'download' => Models\ImpactedItemDownload::class
		));
        return $impacted_item;
    }

    public function subscriptionEntitlement() 
    {
        $subscription_entitlement = $this->_get('subscription_entitlement', Models\SubscriptionEntitlement::class, 
        array( 
			'component' => Models\SubscriptionEntitlementComponent::class
		));
        return $subscription_entitlement;
    }

    public function itemEntitlement() 
    {
        $item_entitlement = $this->_get('item_entitlement', Models\ItemEntitlement::class);
        return $item_entitlement;
    }

    public function inAppSubscription() 
    {
        $in_app_subscription = $this->_get('in_app_subscription', Models\InAppSubscription::class);
        return $in_app_subscription;
    }

    public function entitlementOverride() 
    {
        $entitlement_override = $this->_get('entitlement_override', Models\EntitlementOverride::class);
        return $entitlement_override;
    }

    public function purchase() 
    {
        $purchase = $this->_get('purchase', Models\Purchase::class);
        return $purchase;
    }


    public function unbilledCharges() 
    {
        $unbilled_charges = $this->_getList('unbilled_charges', Models\UnbilledCharge::class,
        array( 
			'tiers' => Models\UnbilledChargeTier::class
		));
        return $unbilled_charges;
    }
    
    public function creditNotes() 
    {
        $credit_notes = $this->_getList('credit_notes', Models\CreditNote::class,
        array( 
			'einvoice' => Models\CreditNoteEinvoice::class, 
			'line_items' => Models\CreditNoteLineItem::class, 
			'discounts' => Models\CreditNoteDiscount::class, 
			'line_item_discounts' => Models\CreditNoteLineItemDiscount::class, 
			'line_item_tiers' => Models\CreditNoteLineItemTier::class, 
			'taxes' => Models\CreditNoteTax::class, 
			'line_item_taxes' => Models\CreditNoteLineItemTax::class, 
			'linked_refunds' => Models\CreditNoteLinkedRefund::class, 
			'allocations' => Models\CreditNoteAllocation::class
		));
        return $credit_notes;
    }
    
    public function advanceInvoiceSchedules() 
    {
        $advance_invoice_schedules = $this->_getList('advance_invoice_schedules', Models\AdvanceInvoiceSchedule::class,
        array( 
			'fixed_interval_schedule' => Models\AdvanceInvoiceScheduleFixedIntervalSchedule::class, 
			'specific_dates_schedule' => Models\AdvanceInvoiceScheduleSpecificDatesSchedule::class
		));
        return $advance_invoice_schedules;
    }
    
    public function hierarchies() 
    {
        $hierarchies = $this->_getList('hierarchies', Models\Hierarchy::class,
        array( 
			
		));
        return $hierarchies;
    }
    
    public function downloads() 
    {
        $downloads = $this->_getList('downloads', Models\Download::class,
        array( 
			
		));
        return $downloads;
    }
    
    public function invoices() 
    {
        $invoices = $this->_getList('invoices', Models\Invoice::class,
        array( 
			'line_items' => Models\InvoiceLineItem::class, 
			'discounts' => Models\InvoiceDiscount::class, 
			'line_item_discounts' => Models\InvoiceLineItemDiscount::class, 
			'taxes' => Models\InvoiceTax::class, 
			'line_item_taxes' => Models\InvoiceLineItemTax::class, 
			'line_item_tiers' => Models\InvoiceLineItemTier::class, 
			'linked_payments' => Models\InvoiceLinkedPayment::class, 
			'dunning_attempts' => Models\InvoiceDunningAttempt::class, 
			'applied_credits' => Models\InvoiceAppliedCredit::class, 
			'adjustment_credit_notes' => Models\InvoiceAdjustmentCreditNote::class, 
			'issued_credit_notes' => Models\InvoiceIssuedCreditNote::class, 
			'linked_orders' => Models\InvoiceLinkedOrder::class, 
			'notes' => Models\InvoiceNote::class, 
			'shipping_address' => Models\InvoiceShippingAddress::class, 
			'billing_address' => Models\InvoiceBillingAddress::class, 
			'einvoice' => Models\InvoiceEinvoice::class
		));
        return $invoices;
    }
    
    public function differentialPrices() 
    {
        $differential_prices = $this->_getList('differential_prices', Models\DifferentialPrice::class,
        array( 
			'tiers' => Models\DifferentialPriceTier::class, 
			'parent_periods' => Models\DifferentialPriceParentPeriod::class
		));
        return $differential_prices;
    }
    
    public function inAppSubscriptions() 
    {
        $in_app_subscriptions = $this->_getList('in_app_subscriptions', Models\InAppSubscription::class,
        array( 
			
		));
        return $in_app_subscriptions;
    }
    

    public function toJson() 
    {
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