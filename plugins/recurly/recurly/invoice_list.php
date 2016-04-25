<?php

class Recurly_InvoiceList extends Recurly_Pager
{
  public static function getOpen($params = null, $client = null) {
    return Recurly_InvoiceList::get(Recurly_Pager::_setState($params, 'open'), $client);
  }

  public static function getCollected($params = null, $client = null) {
    return Recurly_InvoiceList::get(Recurly_Pager::_setState($params, 'collected'), $client);
  }

  public static function getFailed($params = null, $client = null) {
    return Recurly_InvoiceList::get(Recurly_Pager::_setState($params, 'failed'), $client);
  }

  public static function getPastDue($params = null, $client = null) {
    return Recurly_InvoiceList::get(Recurly_Pager::_setState($params, 'past_due'), $client);
  }

  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_INVOICES, $params);
    return new self($uri, $client);
  }

  public static function getForAccount($accountCode, $params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'invoices';
  }
}
