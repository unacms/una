<?php

class Recurly_Account extends Recurly_Resource
{
  protected static $_writeableAttributes;
  protected static $_requiredAttributes;

  function __construct($accountCode = null, $client = null) {
    parent::__construct(null, $client);
    if (!is_null($accountCode))
      $this->account_code = $accountCode;
    $this->address = new Recurly_Address();
  }

  public static function init()
  {
    Recurly_Account::$_writeableAttributes = array(
      'account_code','username','first_name','last_name','vat_number',
      'email','company_name','accept_language','billing_info','address',
      'tax_exempt','entity_use_code','cc_emails'
    );
    Recurly_Account::$_requiredAttributes = array(
      'account_code'
    );

  }

  public function &__get($key)
  {
    if ($key == 'redemption' && parent::__isset('redemptions')) {
      return new Recurly_Stub('redemption', $this->_href . "/redemption", $this->_client);
    } else {
      return parent::__get($key);
    }
  }

  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_Account::uriForAccount($accountCode), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_ACCOUNTS);
  }
  public function update() {
    $this->_save(Recurly_Client::PUT, $this->uri());
  }

  public function close() {
    Recurly_Base::_delete($this->uri(), $this->_client);
    $this->state = 'closed';
  }
  public static function closeAccount($accountCode, $client = null) {
    return Recurly_Base::_delete(Recurly_Account::uriForAccount($accountCode), $client);
  }

  public function reopen() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/reopen');
  }
  public static function reopenAccount($accountCode, $client = null) {
    return Recurly_Base::_put(Recurly_Account::uriForAccount($accountCode) . '/reopen', $client);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_Account::uriForAccount($this->account_code);
  }
  protected static function uriForAccount($accountCode) {
    return Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode);
  }

  protected function getNodeName() {
    return 'account';
  }
  protected function getWriteableAttributes() {
    return Recurly_Account::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return Recurly_Account::$_requiredAttributes;
  }
}

Recurly_Account::init();
