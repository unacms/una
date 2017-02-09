<?php
/**
 * class Recurly_Adjustment
 * @property string $type The type of adjustment to return: charge or credit.
 * @property Recurly_Stub $account The URL of the account for the specified adjustment.
 * @property Recurly_Stub $invoice The URL of the invoice for the specified adjustment.
 * @property string $uuid The unique identifier of the adjustment.
 * @property string $state The state of the adjustments to return: pending or invoiced.
 * @property string $description Description of the adjustment for the adjustment. Max 255 characters.
 * @property string $accounting_code Accounting code. Max of 20 characters.
 * @property string $origin The origin of the adjustment to return: plan, plan_trial, setup_fee, add_on, add_on_trial, one_time, debit, credit, coupon, or carryforward.
 * @property integer $unit_amount_in_cents Positive amount for a charge, negative amount for a credit. Max 10000000.
 * @property integer $quantity Quantity.
 * @property string $original_adjustment_uuid Only shows if adjustment is a credit created from another credit.
 * @property integer $discount_in_cents The discount on the adjustment, in cents.
 * @property integer $tax_in_cents The tax on the adjustment, in cents.
 * @property integer $total_in_cents The total amount of the adjustment, in cents.
 * @property string $currency Currency, 3-letter ISO code.
 * @property boolean $taxable true if the current adjustment is taxable, false if it is not.
 * @property string $tax_type The tax type of the adjustment.
 * @property string $tax_region The tax region of the adjustment.
 * @property string $tax_rate The tax rate of the adjustment.
 * @property boolean $tax_exempt true exempts tax on the charge, false applies tax on the charge. If not defined, then defaults to the Plan and Site settings. This attribute does not work for credits (negative adjustments). Credits are always post-tax. Pre-tax discounts should use the Coupons feature.
 * @property mixed[] $tax_details The nested address information of the adjustment: name, type, tax_rate, tax_in_cents.
 * @property string $tax_code Optional field for EU VAT merchants and Avalara AvaTax Pro merchants. If you are using Recurly's EU VAT feature, you can use values of unknown, physical, or digital. If you have your own AvaTax account configured, you can use Avalara tax codes to assign custom tax rules.
 * @property DateTime $start_date A timestamp associated with when the adjustment began.
 * @property DateTime $end_date A timestamp associated with when the adjustment ended.
 * @property DateTime $created_at A timestamp associated with when the adjustment was created.
 */
class Recurly_Adjustment extends Recurly_Resource
{
  public static function get($adjustment_uuid, $client = null) {
    return Recurly_Base::_get(Recurly_Client::PATH_ADJUSTMENTS . '/' . rawurlencode($adjustment_uuid), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, $this->createUriForAccount());
  }
  public function delete() {
    return Recurly_Base::_delete($this->getHref(), $this->_client);
  }

  /**
   * Allows you to refund this particular item if it's a part of
   * an invoice. It does this by calling the invoice's refund()
   * Only 'invoiced' adjustments can be refunded.
   *
   * @param Integer the quantity you wish to refund, defaults to refunding the entire quantity
   * @param Boolean indicates whether you want this adjustment refund prorated
   * @param String indicates the refund order to apply, valid options: {'credit','transaction'}, defaults to 'credit'
   * @return Recurly_Invoice the new refund invoice
   * @throws Recurly_Error if the adjustment cannot be refunded.
   */
  public function refund($quantity = null, $prorate = false, $refund_apply_order = 'credit') {
    if ($this->state == 'pending') {
      throw new Recurly_Error("Only invoiced adjustments can be refunded");
    }
    $invoice = $this->invoice->get();
    return $invoice->refund($this->toRefundAttributes($quantity, $prorate), $refund_apply_order);
  }

  /**
   * Converts this adjustment into the attributes needed for a refund.
   *
   * @param Integer the quantity you wish to refund, defaults to refunding the entire quantity
   * @param Boolean indicates whether you want this adjustment refund prorated
   * @return Array an array of refund attributes to be fed into invoice->refund()
   */
  public function toRefundAttributes($quantity = null, $prorate = false) {
    if (is_null($quantity)) $quantity = $this->quantity;

    return array('uuid' => $this->uuid, 'quantity' => $quantity, 'prorate' => $prorate);
  }

  protected function createUriForAccount() {
    if (empty($this->account_code))
      throw new Recurly_Error("'account_code' is not specified");

    return (Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($this->account_code) .
            Recurly_Client::PATH_ADJUSTMENTS);
  }

  protected function getNodeName() {
    return 'adjustment';
  }
  protected function getWriteableAttributes() {
    return array(
      'currency', 'unit_amount_in_cents', 'quantity', 'description',
      'accounting_code', 'tax_exempt', 'tax_code', 'start_date', 'end_date',
      'revenue_schedule_type', 'origin'
    );
  }
}
