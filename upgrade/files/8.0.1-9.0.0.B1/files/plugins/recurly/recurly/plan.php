<?php

class Recurly_Plan extends Recurly_Resource
{
  protected static $_writeableAttributes;

  function __construct() {
    parent::__construct();
    $this->setup_fee_in_cents = new Recurly_CurrencyList('setup_fee_in_cents');
    $this->unit_amount_in_cents = new Recurly_CurrencyList('unit_amount_in_cents');
  }

  public static function init()
  {
    Recurly_Plan::$_writeableAttributes = array(
      'plan_code','name','description','success_url','cancel_url',
      'display_donation_amounts','display_quantity','display_phone_number',
      'bypass_hosted_confirmation','unit_name','payment_page_tos_link',
      'plan_interval_length','plan_interval_unit','trial_interval_length',
      'trial_interval_unit','unit_amount_in_cents','setup_fee_in_cents',
      'total_billing_cycles','accounting_code','setup_fee_accounting_code',
      'tax_exempt','tax_code'
    );
  }

  public static function get($planCode, $client = null) {
    return Recurly_Base::_get(Recurly_Plan::uriForPlan($planCode), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_PLANS);
  }
  public function update() {
    $this->_save(Recurly_Client::PUT, $this->uri());
  }

  public function delete() {
    return Recurly_Base::_delete($this->uri(), $this->_client);
  }
  public static function deletePlan($planCode, $client = null) {
    return Recurly_Base::_delete(Recurly_Plan::uriForPlan($planCode), $client);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_Plan::uriForPlan($this->plan_code);
  }
  protected static function uriForPlan($planCode) {
    return Recurly_Client::PATH_PLANS . '/' . rawurlencode($planCode);
  }

  protected function getNodeName() {
    return 'plan';
  }
  protected function getWriteableAttributes() {
    return Recurly_Plan::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }
}

Recurly_Plan::init();
