<?php

class Recurly_CreditPaymentList extends Recurly_Pager
{
  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_CREDIT_PAYMENTS, $params);
    return new self($uri, $client);
  }

  public static function getForAccount($accountCode, $params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_CREDIT_PAYMENTS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'credit_payments';
  }
}
