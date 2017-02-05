<?php

class Recurly_AccountBalance extends Recurly_Resource
{
  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_BALANCE, $client);
  }

  function __construct() {
    parent::__construct();
    $this->balance_in_cents = new Recurly_CurrencyList('balance_in_cents');
  }

  protected function getNodeName() {
    return 'balance';
  }

  protected function getWriteableAttributes() {
   return array();
  }
}
