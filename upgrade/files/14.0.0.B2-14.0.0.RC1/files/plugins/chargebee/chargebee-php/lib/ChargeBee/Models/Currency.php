<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Currency extends Model
{

  protected $allowed = [
    'id',
    'enabled',
    'forexType',
    'currencyCode',
    'isBaseCurrency',
    'manualExchangeRate',
  ];



  # OPERATIONS
  #-----------

  public static function all($env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("currencies","list"), array(), $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("currencies",$id), array(), $env, $headers);
  }

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("currencies"), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("currencies",$id), $params, $env, $headers);
  }

  public static function addSchedule($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("currencies",$id,"add_schedule"), $params, $env, $headers);
  }

  public static function removeSchedule($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("currencies",$id,"remove_schedule"), array(), $env, $headers);
  }

 }

?>