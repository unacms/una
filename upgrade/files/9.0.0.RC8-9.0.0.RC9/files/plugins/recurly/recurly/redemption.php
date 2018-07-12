<?php

/**
 * Class Recurly_CouponRedemption
 * @property Recurly_Stub $coupon The URL of coupon used for this redemption.
 * @property Recurly_Stub $account The URL of account belonging to this redemption.
 * @property Recurly_Stub $subscription The URL of subscription belonging to this redemption.
 * @property string $uuid The unique UUID referencing this redemption.
 * @property boolean $single_use True if this is a single use coupon.
 * @property integer $total_discounted_in_cents Total in cents for the discount issued with this redemption.
 * @property string $currency The currency used at the time of purchase.
 * @property string $state The state of the redemption. Allowed values: [active, inactive].
 * @property string $coupon_code The coupon code of the coupon used.
 * @property DateTime $created_at The date and time the redemption was created in Recurly.
 * @property DateTime $updated_at The date and time the redemption was last updated.
 */
class Recurly_CouponRedemption extends Recurly_Resource
{
  protected static $_redeemUrl;

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
    return array('account_code', 'currency', 'subscription_uuid');
  }
}
