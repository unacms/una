<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class EntitlementOverride extends Model
{

  protected $allowed = [
    'id',
    'entityId',
    'entityType',
    'featureId',
    'featureName',
    'value',
    'name',
    'expiresAt',
  ];



  # OPERATIONS
  #-----------

  public static function addEntitlementOverrideForSubscription($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"entitlement_overrides"), $params, $env, $headers);
  }

  public static function listEntitlementOverrideForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"entitlement_overrides"), $params, $env, $headers);
  }

 }

?>