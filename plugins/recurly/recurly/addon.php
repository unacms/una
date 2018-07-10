<?php

/**
 * class Recurly_Addon
 * @property Recurly_Stub $plan The URL of the associated Recurly_Plan for this add-on.
 * @property Recurly_Stub $measured_unit The URL of the associated Recurly_MeasuredUnit for this add-on.
 * @property string $add_on_code The add-on code. Max of 50 characters.
 * @property string $name The add-on name. Max of 255 characters.
 * @property int $default_quantity Default quantity for the hosted pages, defaults to 1.
 * @property int $default_quantity_on_hosted_page If true, displays a quantity field on the hosted pages for the add-on.
 * @property string $tax_code Optional field for EU VAT merchants and Avalara AvaTax Pro merchants. If you are using Recurly's EU VAT feature, you can use values of: [unknown, physical, digital]. If you have your own AvaTax account configured, you can use Avalara's tax codes to assign custom tax rules.
 * @property Recurly_CurrencyList $unit_amount_in_cents Array of unit amounts with their currency code. Max 10000000. Ex. $5.00 USD would be <USD>500</USD>.
 * @property string $accounting_code Accounting code for invoice line items for the add-on. Max of 20 characters.
 * @property string $add_on_type Whether the add-on is Fixed-Price or Usage-Based. Allowed values: [fixed, usage].
 * @property boolean $optional Whether the add-on is optional for the customer to include in their purchase on the hosted payment page.
 * @property string $usage_type string If add_on_type = usage, you will see usage_type, which can be price or percentage. If price, the price is defined in unit_amount_in_cents. If percentage, the percentage is defined in usage_percentage.
 * @property float $usage_percentage If add_on_type = usage, you will see usage_percentage, which can have a value if usage_type = percentage. Must be between 0.0000 and 100.0000.
 * @property string $revenue_schedule_type Optional field for setting a revenue schedule type. This will determine how revenue for the associated Plan should be recognized. When creating a Plan, if you supply an end_date and end_date available schedule types are never, evenly, at_range_start, or at_range_end.
 * @property DateTime $created_at The date and time the add-on was created.
 * @property DateTime $updated_at The date and time the add-on was last updated.
 */
class Recurly_Addon extends Recurly_Resource
{
  function __construct($href = null, $client = null) {
    parent::__construct($href, $client);
    $this->unit_amount_in_cents = new Recurly_CurrencyList('unit_amount_in_cents');
  }

  public static function get($planCode, $addonCode, $client = null) {
    return Recurly_Base::_get(Recurly_Addon::uriForAddOn($planCode, $addonCode), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_PLANS . '/' . rawurlencode($this->plan_code) . Recurly_Client::PATH_ADDONS);
  }

  public function update() {
    return $this->_save(Recurly_Client::PUT, $this->uri());
  }

  public function delete() {
    return Recurly_Base::_delete($this->uri(), $this->_client);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_Addon::uriForAddOn($this->plan_code, $this->add_on_code);
  }
  protected static function uriForAddOn($planCode, $addonCode) {
    return (Recurly_Client::PATH_PLANS . '/' . rawurlencode($planCode) .
            Recurly_Client::PATH_ADDONS . '/' . rawurlencode($addonCode));
  }

  protected function getNodeName() {
    return 'add_on';
  }
  protected function getWriteableAttributes() {
    return array(
      'add_on_code', 'name', 'display_quantity', 'default_quantity',
      'unit_amount_in_cents', 'accounting_code', 'tax_code',
      'measured_unit_id', 'usage_type', 'add_on_type', 'revenue_schedule_type',
      'optional', 'display_quantity_on_hosted_page'
    );
  }
}
