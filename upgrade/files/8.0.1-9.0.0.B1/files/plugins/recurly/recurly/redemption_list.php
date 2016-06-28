<?php

class Recurly_CouponRedemptionList extends Recurly_Pager
{
  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_COUPON_REDEMPTIONS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'redemptions';
  }
}
