<?php

class Recurly_TransactionList extends Recurly_Pager
{
  public static function getSuccessful($params = null, $client = null) {
    return Recurly_TransactionList::get(Recurly_Pager::_setState($params, 'successful'), $client);
  }

  public static function getVoided($params = null, $client = null) {
    return Recurly_TransactionList::get(Recurly_Pager::_setState($params, 'voided'), $client);
  }

  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_TRANSACTIONS, $params);
    return new self($uri, $client);
  }

  public static function getForAccount($accountCode, $params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_TRANSACTIONS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'transactions';
  }
}
