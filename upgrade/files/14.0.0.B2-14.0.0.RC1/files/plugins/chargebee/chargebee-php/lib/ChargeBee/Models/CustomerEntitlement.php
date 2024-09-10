<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class CustomerEntitlement extends Model
{

  protected $allowed = [
    'customerId',
    'subscriptionId',
    'featureId',
    'value',
    'name',
    'isEnabled',
  ];



  # OPERATIONS
  #-----------

  public static function entitlementsForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"customer_entitlements"), $params, $env, $headers);
  }

 }

?>