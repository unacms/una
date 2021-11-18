<?php

class Recurly_CouponList extends Recurly_Pager
{
  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_COUPONS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'coupons';
  }
}
