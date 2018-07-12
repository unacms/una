<?php

/**
 * Class Recurly_AccountBalance
 * @property Recurly_Stub $account The associated Recurly_Account for this balance.
 * @property boolean $past_due The account's past due status.
 * @property Recurly_CurrencyList $balance_in_cents The account balance in cents for each currency.
 */
class Recurly_AccountBalance extends Recurly_Resource
{
  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_BALANCE, $client);
  }

  function __construct($href = null, $client = null) {
    parent::__construct($href, $client);
    $this->balance_in_cents = new Recurly_CurrencyList('balance_in_cents');
  }

  protected function getNodeName() {
    return 'balance';
  }

  protected function getWriteableAttributes() {
   return array();
  }
}
