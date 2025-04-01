<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class UsageEvent extends Model
{

  protected $allowed = [
    'subscriptionId',
    'deduplicationId',
    'usageTimestamp',
    'properties',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "properties" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("usage_events"), $params, $env, $headers, "ingest", true, $jsonKeys);
  }

  public static function batchIngest($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "properties" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("batch","usage_events"), $params, $env, $headers, "ingest", true, $jsonKeys);
  }

 }

?>