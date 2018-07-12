<?php

/**
 * Class Recurly_Coupon
 * @property Recurly_Stub $redemptions The URL of Recurly_CouponRedemptions for this coupon.
 * @property int $id The unique integer identifier of the coupon.
 * @property string $coupon_code Unique code to identify and redeem the coupon. This code may only contain the following characters: [a-z A-Z 0-9 + - _ ]. Max of 50 characters.
 * @property string $name Coupon name.
 * @property string $description Description of the coupon shown on the hosted payment pages.
 * @property string $discount_type The type of discount. Allowed values: [percent, dollars, free_trial].
 * @property int $discount_in_cents Mapping of discount amounts by currency if discount_type is dollars. Max 10000000.
 * @property int $discount_percent Discount percentage if discount_type is percent. Example `coupon->discount_percent = 10; // 10%`
 * @property string $invoice_description Description of the coupon on the invoice.
 * @property int $max_redemptions Maximum number of accounts that may use the coupon before it can no longer be redeemed.
 * @property boolean $applies_to_all_plans The coupon is valid for all plans if true, defaults to true.
 * @property string $duration Allowed values: [forever, single_use, temporal].  If single_use, the coupon applies to the first invoice only. If temporal the coupon will apply to invoices for the duration determined by the temporal_unit and temporal_amount attributes.
 * @property string $temporal_unit Allowed values: [day, week, month, year]. If duration is temporal then temporal_unit is multiplied by temporal_amount to define the duration that the coupon will be applied to invoices for.
 * @property integer $temporal_amount If duration is temporal then temporal_amount is an integer which is multiplied by temporal_unit to define the duration that the coupon will be applied to invoices for.
 * @property boolean $applies_to_non_plan_charges The coupon is valid for one-time, non-plan charges if true, defaults to false.
 * @property string $redemption_resource Whether the discount is for all eligible charges on the account, or only a specific subscription. Allowed values: [account, subscription].
 * @property int $max_redemptions_per_account The number of times the coupon can be redeemed on a specific account. null is the default and means unlimited.
 * @property string $coupon_type Allowed values: [single_code, bulk]. Bulk coupons will require a unique_code_template and will generate unique codes through the generate endpoint.
 * @property string $unique_code_template The template for generating unique codes. See rules in the coupon docs: https://dev.recurly.com/docs/create-coupon
 * @property string[] $plan_codes Array of plan_codes the coupon applies to, if applies_to_all_plans is false.
 * @property int $free_trial_amount Only relevant when the coupon type is free_trial. The free_trial_amount is used together with free_trial_unit to define the length of a free trial coupon. For example, a 2 week free trial would be defined as free_trial_amount = 2 and free_trial_unit = Week.
 * @property string $free_trial_unit Only relevant when the coupon type is free_trial. Allowed values are day or week or month. free_trial_unit is used together with free_trial_unit to define the length of a free trial coupon. For example, a 2 week free trial would be defined as free_trial_amount = 2 and free_trial_unit = Week.
 * @property DateTime $redeem_by_date Last date to redeem the coupon, defaults to no date.
 * @property DateTime $created_at The date and time the coupon was created.
 * @property DateTime $updated_at The date and time the coupon was last updated.
 * @property DateTime $deleted_at The date and time the coupon was deleted.
 */
class Recurly_Coupon extends Recurly_Resource
{
  protected $_redeemUrl;

  function __construct($href = null, $client = null) {
    parent::__construct($href, $client);
    $this->discount_in_cents = new Recurly_CurrencyList('discount_in_cents');
  }

  public static function get($couponCode, $client = null) {
    return Recurly_Base::_get(Recurly_Coupon::uriForCoupon($couponCode), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_COUPONS);
  }

  public function redeemCoupon($accountCode, $currency, $subscriptionUUID = null) {
    if ($this->state != 'redeemable') {
      throw new Recurly_Error('Coupon is not redeemable.');
    }

    $redemption = new Recurly_CouponRedemption(null, $this->_client);
    $redemption->account_code = $accountCode;
    $redemption->currency = $currency;
    $redemption->subscription_uuid = $subscriptionUUID;

    foreach ($this->_links as $link) {
      if ($link->name == 'redeem') {
        $redemption->_save(strtoupper($link->method), $link->href);
        return $redemption;
      }
    }
  }

  public function update() {
    $this->_save(Recurly_Client::PUT, $this->uri(), $this->createUpdateXML());
  }

  public function restore() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/restore', $this->createUpdateXML());
  }

  public function delete() {
    return Recurly_Base::_delete($this->uri(), $this->_client);
  }
  public static function deleteCoupon($couponCode, $client = null) {
    return Recurly_Base::_delete(Recurly_Coupon::uriForCoupon($couponCode), $client);
  }

  // generates the xml needed for a coupon update
  // only uses the updateable attributes
  public function createUpdateXML() {
    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));

    foreach ($this->getUpdatableAttributes() as $attr) {
      $val = $this->$attr;

      if ($val instanceof DateTime) {
        $val = $val->format('c');
      }

      $root->appendChild($doc->createElement($attr, $val));
    }

    return $this->renderXML($doc);
  }

  public function generate($number) {
    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('number_of_unique_codes', $number));

    $response = $this->_client->request(Recurly_Client::POST, $this->uri() . '/generate', $this->renderXML($doc));
    $response->assertValidResponse();

    $coupons = array();
    foreach (new Recurly_UniqueCouponCodeList($response->headers['Location'], $this->_client) as $coupon) {
      $coupons[] = $coupon;
      if (count($coupons) == $number) break;
    }

    return $coupons;
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_Coupon::uriForCoupon($this->coupon_code);
  }
  protected static function uriForCoupon($couponCode) {
    return Recurly_Client::PATH_COUPONS . '/' . rawurlencode($couponCode);
  }

  protected function getNodeName() {
    return 'coupon';
  }
  protected function getWriteableAttributes() {
    return array(
      'coupon_code', 'name', 'discount_type', 'redeem_by_date', 'single_use',
      'applies_for_months', 'duration', 'temporal_unit', 'temporal_amount',
      'max_redemptions', 'applies_to_all_plans', 'discount_percent',
      'discount_in_cents', 'plan_codes', 'hosted_description',
      'invoice_description', 'applies_to_non_plan_charges', 'redemption_resource',
      'max_redemptions_per_account', 'coupon_type', 'unique_code_template',
      'unique_coupon_codes', 'discount_type', 'free_trial_amount',
      'free_trial_unit', 'description'
    );
  }
  protected function getUpdatableAttributes() {
    return array(
      'name', 'max_redemptions', 'max_redemptions_per_account',
      'hosted_description', 'invoice_description', 'redeem_by_date',
      'description'
    );
  }
}
