<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class AttachedItem extends Model
{

  protected $allowed = [
    'id',
    'parentItemId',
    'itemId',
    'type',
    'status',
    'quantity',
    'quantityInDecimal',
    'billingCycles',
    'chargeOnEvent',
    'chargeOnce',
    'createdAt',
    'resourceVersion',
    'updatedAt',
    'channel',
  ];



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("items",$id,"attached_items"), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("attached_items",$id), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("attached_items",$id), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("attached_items",$id,"delete"), $params, $env, $headers);
  }

  public static function all($id, $params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("items",$id,"attached_items"), $params, $env, $headers);
  }

 }

?>