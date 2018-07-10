<?php

/**
 * Class Recurly_Usage
 * @property Recurly_Stub $measured_unit The URL to the Recurly_MeasuredUnit associated with this usage.
 * @property integer $amount The amount of usage. Can be positive, negative, or 0. No decimals allowed, we will strip them. If the usage-based add-on is billed with a percentage, your usage will be a monetary amount you will want to format in cents. (e.g. - $5.00 is "500").
 * @property string $merchant_tag Custom field great for recording the id in your own system associated with the usage, so you can provide auditable usage displays to your customers using a GET on this endpoint.
 * @property string $usage_type Whether the associated add-on has a pricing model of "price per unit" (price) or "percentage of an amount" (percentage).
 * @property integer $unit_amount_in_cents If usage_type = price, this is the price of the add-on at the usage_timestamp time and the price at which the usage will be billed on the invoice.
 * @property float $usage_percentage If usage_type = percentage, this is the percentage for the add-on at the usage_timestamp time and the percentage at which the usage will be billed on the invoice.
 * @property DateTime $recording_timestamp When the usage was recorded in your system.
 * @property DateTime $usage_timestamp When the usage actually happened. This will define the line item dates this usage is billed under and is important for revenue recognition.
 * @property DateTime $created_at When the usage record was created in Recurly.
 * @property DateTime $updated_at When the usage record was updated. Will be null after creation.
 * @property DateTime $billed_at When the usage record was billed on an invoice.
 */
class Recurly_Usage extends Recurly_Resource
{
  var $subUuid;
  var $addOnCode;

  public static function build($subUuid, $addOnCode, $client = null) {
    $usage = new self(null, $client);
    $usage->subUuid = $subUuid;
    $usage->addOnCode = $addOnCode;
    return $usage;
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Usage::uriForUsages($this->subUuid, $this->addOnCode));
  }

  public function update() {
    return $this->_save(Recurly_Client::PUT, $this->uri());
  }

  public static function get($subUuid, $addOnCode, $usageId, $client = null) {
    return Recurly_Base::_get(self::uriForUsage($subUuid, $addOnCode, $usageId), $client);
  }

  protected function uri() {
    return $this->getHref();
  }

  protected static function uriForUsages($subUuid, $addOnCode) {
    return Recurly_Client::PATH_SUBSCRIPTIONS . '/' . rawurlencode($subUuid) . Recurly_Client::PATH_ADDONS . '/' . rawurlencode($addOnCode) . Recurly_Client::PATH_USAGE;
  }

  protected static function uriForUsage($subUuid, $addOnCode, $usageId) {
    return Recurly_Usage::uriForUsages($subUuid, $addOnCode) . '/' . rawurlencode($usageId);
  }

  protected function getNodeName() {
    return 'usage';
  }
  protected function getWriteableAttributes() {
    return array(
      'amount', 'merchant_tag', 'usage_type', 'unit_amount_in_cents',
      'billed_at', 'recording_timestamp', 'usage_timestamp', 'measured_unit'
    );
  }
}
