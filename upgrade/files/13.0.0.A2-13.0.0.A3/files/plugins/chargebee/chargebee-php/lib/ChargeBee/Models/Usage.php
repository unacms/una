<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Usage extends Model
{

  protected $allowed = [
    'id',
    'usageDate',
    'subscriptionId',
    'itemPriceId',
    'invoiceId',
    'lineItemId',
    'quantity',
    'source',
    'note',
    'resourceVersion',
    'updatedAt',
    'createdAt',
  ];



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"usages"), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"usages"), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"delete_usage"), $params, $env, $headers);
  }

  public static function all($params, $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("usages"), $params, $env, $headers);
  }

  public static function pdf($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("usages","pdf"), $params, $env, $headers);
  }

 }

?>