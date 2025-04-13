<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class RecordedPurchase extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'appId',
    'source',
    'status',
    'omnichannelTransactionId',
    'createdAt',
    'resourceVersion',
    'linkedOmnichannelSubscriptions',
    'errorDetail',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("recorded_purchases"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("recorded_purchases",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>