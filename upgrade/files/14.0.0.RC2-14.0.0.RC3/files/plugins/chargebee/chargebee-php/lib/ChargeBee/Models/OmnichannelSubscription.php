<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class OmnichannelSubscription extends Model
{

  protected $allowed = [
    'id',
    'idAtSource',
    'appId',
    'source',
    'customerId',
    'createdAt',
    'resourceVersion',
    'omnichannelSubscriptionItems',
  ];



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("omnichannel_subscriptions",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("omnichannel_subscriptions"), $params, $env, $headers);
  }

  public static function omnichannelTransactionsForOmnichannelSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("omnichannel_subscriptions",$id,"omnichannel_transactions"), $params, $env, $headers);
  }

 }

?>