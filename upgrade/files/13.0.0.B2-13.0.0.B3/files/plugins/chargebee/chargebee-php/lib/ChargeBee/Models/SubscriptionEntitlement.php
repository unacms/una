<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class SubscriptionEntitlement extends Model
{

  protected $allowed = [
    'id',
    'subscriptionId',
    'featureId',
    'featureName',
    'featureUnit',
    'value',
    'name',
    'isOverridden',
    'isEnabled',
    'expiresAt',
    'components',
  ];



  # OPERATIONS
  #-----------

  public static function subscriptionEntitlementsForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"subscription_entitlements"), $params, $env, $headers);
  }

  public static function setSubscriptionEntitlementAvailability($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"subscription_entitlements/set_availability"), $params, $env, $headers);
  }

 }

?>