<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class ItemEntitlement extends Model
{

  protected $allowed = [
    'id',
    'itemId',
    'itemType',
    'featureId',
    'featureName',
    'value',
    'name',
  ];



  # OPERATIONS
  #-----------

  public static function itemEntitlementsForItem($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("items",$id,"item_entitlements"), $params, $env, $headers);
  }

  public static function itemEntitlementsForFeature($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("features",$id,"item_entitlements"), $params, $env, $headers);
  }

  public static function addItemEntitlements($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id,"item_entitlements"), $params, $env, $headers);
  }

  public static function upsertOrRemoveItemEntitlementsForItem($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("items",$id,"item_entitlements"), $params, $env, $headers);
  }

 }

?>