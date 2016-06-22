<?php

class Recurly_Usage extends Recurly_Resource
{
  var $subUuid;
  var $addOnCode;

  protected static $_writeableAttributes;

  public static function init()
  {
    Recurly_Usage::$_writeableAttributes = array(
      'amount','merchant_tag','usage_type','unit_amount_in_cents',
      'billed_at','recording_timestamp','usage_timestamp','measured_unit'
    );
  }

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
    return Recurly_Usage::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }
}

Recurly_Usage::init();
