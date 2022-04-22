<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class CouponCode extends Model
{

  protected $allowed = [
    'code',
    'status',
    'couponId',
    'couponSetId',
    'couponSetName',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_codes"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("coupon_codes",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("coupon_codes"), $params, $env, $headers);
  }

  public static function archive($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupon_codes",$id,"archive"), array(), $env, $headers);
  }

 }

?>