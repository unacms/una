<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Ramp extends Model
{

  protected $allowed = [
    'id',
    'description',
    'subscriptionId',
    'effectiveFrom',
    'status',
    'createdAt',
    'resourceVersion',
    'updatedAt',
    'itemsToAdd',
    'itemsToUpdate',
    'couponsToAdd',
    'discountsToAdd',
    'itemTiers',
    'itemsToRemove',
    'couponsToRemove',
    'discountsToRemove',
    'deleted',
    'statusTransitionReason',
  ];



  # OPERATIONS
  #-----------

  public static function createForSubscription($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"create_ramp"), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("ramps",$id,"update"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("ramps",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("ramps",$id,"delete"), array(), $env, $headers);
  }

  public static function all($params, $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("ramps"), $params, $env, $headers);
  }

 }

?>