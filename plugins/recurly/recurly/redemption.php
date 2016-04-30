<?php

class Recurly_CouponRedemption extends Recurly_Resource
{
  protected static $_writeableAttributes;
  protected static $_redeemUrl;

  public static function init()
  {
    Recurly_CouponRedemption::$_writeableAttributes = array('account_code','currency','subscription_uuid');
  }

  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_CouponRedemption::uriForAccount($accountCode), $client);
  }

  public function delete($accountCode = null) {
    return Recurly_Base::_delete($this->uri($accountCode), $this->_client);
  }

  protected function uri($accountCode = null) {
    if (!empty($this->_href))
      return $this->getHref();
    else if(!empty($accountCode))
      return Recurly_CouponRedemption::uriForAccount($accountCode);
    else
      return false;
  }

  protected static function uriForAccount($accountCode) {
    return Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_COUPON_REDEMPTION;
  }

  protected function getNodeName() {
    return 'redemption';
  }
  protected function getWriteableAttributes() {
    return Recurly_CouponRedemption::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }
}

Recurly_CouponRedemption::init();
