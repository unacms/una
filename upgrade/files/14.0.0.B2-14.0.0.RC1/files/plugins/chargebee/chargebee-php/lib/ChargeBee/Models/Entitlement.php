<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Entitlement extends Model
{

  protected $allowed = [
    'id',
    'entityId',
    'entityType',
    'featureId',
    'featureName',
    'value',
    'name',
  ];



  # OPERATIONS
  #-----------

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("entitlements"), $params, $env, $headers);
  }

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("entitlements"), $params, $env, $headers);
  }

 }

?>