<?php

/**
 * Class Recurly_Subscription
 * @property Recurly_Stub $gift_card The gift card object is required in order to redeem a redemption code.
 * @property string $redemption_code The redemption code for the gift card being redeemed.
 * @property string $plan_code plan_code for the subscription.
 * @property Recurly_Stub $account Nested account attributes.
 * @property string $currency Currency for the subscription.
 * @property mixed[] $subscription_add_ons Nested add-ons.
 * @property string $coupon_code Optional coupon code to redeem on the account and discount the subscription. Please note, the subscription request will fail if the coupon is invalid.
 * @property integer $unit_amount_in_cents Override the unit amount of the subscription plan by setting this value in cents. If not provided, the subscription will inherit the price from the subscription plan for the provided currency. Max 10000000.
 * @property integer $quantity Optionally override the default quantity of 1.
 * @property DateTime $trial_ends_at If set, overrides the default trial behavior for the subscription. This must be a date and time, preferably in UTC. The date must be in the future.
 * @property DateTime $starts_at If set, the subscription will begin in the future on this date. The subscription will apply the setup fee and trial period, unless the plan has no trial.
 * @property integer $total_billing_cycles Determines the length of the subscription’s initial term. Defaults to plan’s total billing cycles value unless overwritten when creating the subscription or editing subscription.
 * @property DateTime $first_renewal_date Indicates a date at which the first renewal should occur. Subsequent renewals will be offset from this date. The first invoice will be prorated appropriately so that the customer only pays for the portion of the first billing period for which the subscription applies. Useful for forcing a subscription to renew on the first of the month.
 * @property string $collection_method Optional field to set the collection for an invoice as automatic or manual. The default is automatic if it's not set.
 * @property integer $net_terms Integer representing the number of days after an invoice's creation that the invoice will become past due. If an invoice's net terms are set to 0, it is due 'On Receipt' and will become past due 24 hours after it’s created. If an invoice is due net 30, it will become past due at 31 days exactly. Defaults to 0.
 * @property string $po_number Optional notes field. Attach a PO number to the invoice.
 * @property boolean $bulk Optional field to be used only when needing to bypass the 60 second limit on creating subscriptions. Should only be used when creating subscriptions in bulk from the API. Set to 'true' or 'false'. Defaults to 'false'.
 * @property string $terms_and_conditions Optional notes field. This will default to the Terms and Conditions text specified on the Invoice Settings page in your Recurly admin. Specify custom notes with this tag to add or override Terms and Conditions. Custom notes will stay with a subscription on all renewals.
 * @property string $customer_notes Optional notes field. This will default to the Customer Notes text specified on the Invoice Settings page in your Recurly admin. Specify custom notes with this tag to add or override Customer Notes. Custom notes will stay with a subscription on all renewals.
 * @property string $vat_reverse_charge_notes VAT Reverse Charge Notes only appear if you have EU VAT enabled or are using your own Avalara AvaTax account and the customer is in the EU, has a VAT number, and is in a different country than your own. This will default to the VAT Reverse Charge Notes text specified on the Tax Settings page in your Recurly admin, unless custom notes were created with the original subscription. Specify custom notes with this tag to add or override VAT Reverse Charge Notes. Custom notes will stay with a subscription on all renewals.
 * @property DateTime $bank_account_authorized_at Merchants importing recurring subscriptions paid with ACH into Recurly can backdate the subscription's authorization with this attribute using an ISO 8601 timestamp. This timestamp is used for alerting customers to reauthorize in 3 years in accordance with NACHA rules. If a subscription becomes inactive or the billing info is no longer a bank account, this timestamp is cleared.
 * @property string $add_on_code The code for the Add-On.
 * @property string $usage_percentage If add_on_type = usage and usage_type = percentage, you can set a custom usage_percentage for the subscription add-on. Must be between 0.0000 and 100.0000.
 * @property string $revenue_schedule_type Optional field for setting a revenue schedule type. This will determine how revenue for the associated Subscription Add-On should be recognized. When creating a Subscription Add-On, available schedule types are never, evenly, at_range_start, or at_range_end. If no revenue_schedule_type is set, the Subscription Add-On will inherit the revenue_schedule_type from its Plan Add-On.
 * @property boolean $imported_trial Optionally set true to denote that this subscription was imported from a trial.
 * @property string $credit_customer_notes Allows merchant to set customer notes on a credit invoice. Will be ignored if no credit invoice is created.
 * @property DateTime $paused_at The datetime when the subscription will be (or was) paused.
 * @property integer $remaining_pause_cycles The number of billing cycles that the subscription will be paused.
 * @property boolean $auto_renew Determines whether subscription will auto renew for another term at the end of current term. Defaults to plan value unless overwritten when creating subscription or editing subscription.
 * @property integer $remaining_billing_cycles Remaining billing periods in the current subscription term. Decrements from the total number of billing periods in the current term. Will always be 0 if total billing cycles is 1.
 * @property integer $renewal_billing_cycles Determines the renewal subscription term Defaults to plan’s total billing cycles value unless overwritten when creating the subscription or editing subscription.
 * @property DateTime $current_period_started_at Start date of the subscription’s current billing period.
 * @property DateTime $current_period_ends_at End date of the subscription’s current billing period.
 * @property DateTime $first_bill_date Previously named first_renewal_date. Forces the subscription’s next billing period start date. Subsequent billing period start dates will be offset from this date. The first invoice will be prorated appropriately so that the customer pays for the portion of the first billing period for which the subscription applies.
 * @property DateTime $next_bill_date Previously named next_renewal_date. Specifies a future date that the subscription’s next  billing period should be billed.
 * @property DateTime $current_term_started_at Start date of the subscription’s current term. Will equal the future start date if subscription was created in the future state.
 * @property DateTime $current_term_ends_at End date of the subscription’s current term. Will be null if subscription has future start date.
 */
class Recurly_Subscription extends Recurly_Resource
{
  public function __construct($href = null, $client = null) {
    parent::__construct($href, $client);
    $this->subscription_add_ons = array();
    $this->custom_fields = new Recurly_CustomFieldList();
  }

  public static function get($uuid, $client = null) {
    return Recurly_Base::_get(Recurly_Subscription::uriForSubscription($uuid), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_SUBSCRIPTIONS);
  }

  /**
   * Preview the creation and check for errors.
   *
   * Note: once preview() has been called you will not be able to call create()
   * or save() without reassiging all the attributes.
   */
  public function preview() {
    if ($this->uuid) {
      $this->_save(Recurly_Client::POST, $this->uri() . '/preview');
    } else {
      $this->_save(Recurly_Client::POST, Recurly_Client::PATH_SUBSCRIPTIONS . '/preview');
    }
  }

  /**
   * Cancel the subscription; it will remain active until it renews.
   */
  public function cancel() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/cancel');
  }
  /**
   * Reactivate a canceled subscription. The subscription will return back to the active,
   * auto renewing state.
   */
  public function reactivate() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/reactivate');
  }

  /**
   * Make an update that takes effect immediately.
   */
  public function updateImmediately() {
    $this->timeframe = 'now';
    $this->_save(Recurly_Client::PUT, $this->uri());
  }

  /**
   * Make an update that applies when the subscription renews.
   */
  public function updateAtRenewal() {
    $this->timeframe = 'renewal';
    $this->_save(Recurly_Client::PUT, $this->uri());
  }


  /**
   * Terminate the subscription immediately and issue a full refund of the last renewal
   */
  public function terminateAndRefund() {
    $this->terminate('full');
  }
  /**
   * Terminate the subscription immediately and issue a prorated/partial refund of the last renewal
   */
  public function terminateAndPartialRefund() {
    $this->terminate('partial');
  }
  /**
   * Terminate the subscription immediately without a refund
   */
  public function terminateWithoutRefund() {
    $this->terminate('none');
  }
  private function terminate($refundType) {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/terminate?refund=' . $refundType);
  }

  /**
   * Postpone a subscription's renewal date.
   *
   * @param String ISO8601 DateTime String, postpone the subscription to this date
   * @param Boolean bulk is for making bulk updates, setting to true bypasses api check for accidental duplicate subscriptions.
   **/
  public function postpone($nextRenewalDate, $bulk = false) {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/postpone?next_renewal_date=' . $nextRenewalDate . '&bulk=' . ((bool) $bulk));
  }

  /**
   * Updates the notes fields of the subscription without generating a SubscriptionChange.
   *
   * @param array of notes, allowed keys: (customer_notes, terms_and_conditions, vat_reverse_charge_notes)
   **/
  public function updateNotes($notes) {
    $this->setValues($notes)->_save(Recurly_Client::PUT, $this->uri() . '/notes');
  }

  /**
    * Pauses a subscription or cancels a scheduled pause.
    *
    * - For an active subscription without a pause scheduled already,
    * this will schedule a pause period to begin at the next renewal
    * date for the specified number of billing cycles (remaining_pause_cycles).
    * - For an active subscription with a scheduled pause, this will update the remaining
    * pause cycles with the new value sent. When zero (0) remaining_pause_cycles
    * is sent for a subscription with a scheduled pause, the pause will be canceled.
    * - For a paused subscription, the remaining_pause_cycles will adjust the
    * length of the current pause period. Sending zero (0) in the remaining_pause_cycles
    * field will cause the subscription to be resumed at the next renewal date.
    *
    * @param integer remaining_pause_cycles The number of billing cycles that the subscription will be paused.
    **/
  public function pause($remaining_pause_cycles) {
    $doc = $this->createDocument();
    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('remaining_pause_cycles', $remaining_pause_cycles));
    $this->_save(Recurly_Client::PUT, $this->uri() . '/pause', $this->renderXML($doc));
  }

  /**
   * Resumes a paused subscription.
   *
   * For a paused subscription, this will immediately resume the subscription
   * from the pause, produce an invoice, and return the newly resumed subscription.
   * Any at-renewal subscription changes will be immediately applied
   * when the subscription resumes.
   **/
  public function resume() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/resume');
  }

  public function buildUsage($addOnCode, $client = null) {
    return Recurly_Usage::build($this->uuid, $addOnCode, $client);
  }

  public function usages($addOnCode, $params = null) {
    return Recurly_UsageList::get($this->uuid, $addOnCode, $params);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else if (!empty($this->uuid))
      return Recurly_Subscription::uriForSubscription($this->uuid);
    else
      throw new Recurly_Error("Subscription UUID not specified");
  }
  protected static function uriForSubscription($uuid) {
    return Recurly_Client::PATH_SUBSCRIPTIONS . '/' . rawurlencode($uuid);
  }

  protected function populateXmlDoc(&$doc, &$node, &$obj, $nested = false) {
    if ($this->isEmbedded($node, 'subscriptions')) {
      $subscriptionNode = $node->appendChild($doc->createElement($this->getNodeName()));
      parent::populateXmlDoc($doc, $subscriptionNode, $obj, $nested);
    } else {
      parent::populateXmlDoc($doc, $node, $obj, $nested);
    }
  }
  protected function getNodeName() {
    return 'subscription';
  }
  protected function getWriteableAttributes() {
    return array(
      'account', 'plan_code', 'coupon_code', 'coupon_codes',
      'unit_amount_in_cents', 'quantity', 'currency', 'starts_at',
      'trial_ends_at', 'total_billing_cycles', 'first_renewal_date',
      'timeframe', 'subscription_add_ons', 'net_terms', 'po_number',
      'collection_method', 'cost_in_cents', 'remaining_billing_cycles', 'bulk',
      'terms_and_conditions', 'customer_notes', 'vat_reverse_charge_notes',
      'bank_account_authorized_at', 'revenue_schedule_type', 'gift_card',
      'shipping_address', 'shipping_address_id', 'imported_trial',
      'remaining_pause_cycles', 'custom_fields', 'auto_renew',
      'renewal_billing_cycles'
    );
  }
}
