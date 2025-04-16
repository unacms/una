<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PromotionalCredit extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'type',
    'amountInDecimal',
    'amount',
    'currencyCode',
    'description',
    'creditType',
    'reference',
    'closingBalance',
    'doneBy',
    'createdAt',
  ];



  # OPERATIONS
  #-----------

  public static function add($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("promotional_credits","add"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function deduct($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("promotional_credits","deduct"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function set($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("promotional_credits","set"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("promotional_credits"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("promotional_credits",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>