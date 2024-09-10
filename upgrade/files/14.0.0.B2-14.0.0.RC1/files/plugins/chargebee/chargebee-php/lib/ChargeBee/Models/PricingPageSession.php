<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PricingPageSession extends Model
{

  protected $allowed = [
    'id',
    'url',
    'createdAt',
    'expiresAt',
  ];



  # OPERATIONS
  #-----------

  public static function createForNewSubscription($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("pricing_page_sessions","create_for_new_subscription"), $params, $env, $headers);
  }

  public static function createForExistingSubscription($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("pricing_page_sessions","create_for_existing_subscription"), $params, $env, $headers);
  }

 }

?>