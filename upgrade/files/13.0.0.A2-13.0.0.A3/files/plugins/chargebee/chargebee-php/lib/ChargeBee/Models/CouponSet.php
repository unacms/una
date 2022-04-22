<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class CouponSet extends Model
{

  protected $allowed = [
    'id',
    'couponId',
    'name',
    'totalCount',
    'redeemedCount',
    'archivedCount',
    'metaData',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets"), $params, $env, $headers);
  }

  public static function addCouponCodes($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"add_coupon_codes"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("coupon_sets"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("coupon_sets",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"update"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"delete"), array(), $env, $headers);
  }

  public static function deleteUnusedCouponCodes($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"delete_unused_coupon_codes"), array(), $env, $headers);
  }

 }

?>