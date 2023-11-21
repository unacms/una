<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class NonSubscription extends Model
{

  protected $allowed = [
    'appId',
    'invoiceId',
    'customerId',
    'chargeId',
  ];



  # OPERATIONS
  #-----------

  public static function processReceipt($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("non_subscriptions",$id,"one_time_purchase"), $params, $env, $headers);
  }

 }

?>