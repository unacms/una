<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Gift extends Model
{

  protected $allowed = [
    'id',
    'status',
    'scheduledAt',
    'autoClaim',
    'noExpiry',
    'claimExpiryDate',
    'resourceVersion',
    'updatedAt',
    'gifter',
    'giftReceiver',
    'giftTimelines',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("gifts"), $params, $env, $headers);
  }

  public static function createForItems($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("gifts","create_for_items"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("gifts",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("gifts"), $params, $env, $headers);
  }

  public static function claim($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("gifts",$id,"claim"), array(), $env, $headers);
  }

  public static function cancel($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("gifts",$id,"cancel"), array(), $env, $headers);
  }

  public static function updateGift($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("gifts",$id,"update_gift"), $params, $env, $headers);
  }

 }

?>