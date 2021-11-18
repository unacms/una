<?php

class ChargeBee_CouponSet extends ChargeBee_Model
{

  protected $allowed = array('id', 'couponId', 'name', 'totalCount', 'redeemedCount', 'archivedCount', 'metaData'
);



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_sets"), $params, $env, $headers);
  }

  public static function addCouponCodes($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_sets",$id,"add_coupon_codes"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("coupon_sets"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("coupon_sets",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_sets",$id,"update"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_sets",$id,"delete"), array(), $env, $headers);
  }

  public static function deleteUnusedCouponCodes($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupon_sets",$id,"delete_unused_coupon_codes"), array(), $env, $headers);
  }

 }

?>