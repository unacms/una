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
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function addCouponCodes($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"add_coupon_codes"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("coupon_sets"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("coupon_sets",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"update"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function deleteUnusedCouponCodes($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupon_sets",$id,"delete_unused_coupon_codes"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>