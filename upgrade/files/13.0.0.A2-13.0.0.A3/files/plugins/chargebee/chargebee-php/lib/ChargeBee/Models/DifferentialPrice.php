<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class DifferentialPrice extends Model
{

  protected $allowed = [
    'id',
    'itemPriceId',
    'parentItemId',
    'price',
    'priceInDecimal',
    'status',
    'resourceVersion',
    'updatedAt',
    'createdAt',
    'modifiedAt',
    'tiers',
    'currencyCode',
    'parentPeriods',
  ];



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("item_prices",$id,"differential_prices"), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("differential_prices",$id), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("differential_prices",$id), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("differential_prices",$id,"delete"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("differential_prices"), $params, $env, $headers);
  }

 }

?>