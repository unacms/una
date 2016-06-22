<?php

class ChargeBee_CouponCode extends ChargeBee_Model
{

  protected $allowed = array('code', 'status', 'couponId', 'couponSetName'
);



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_codes"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("coupon_codes",$id), array(), $env, $headers);
  }

  public static function archive($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_codes",$id,"archive"), array(), $env, $headers);
  }

 }

?>