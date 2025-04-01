<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Purchase extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'createdAt',
    'modifiedAt',
    'subscriptionIds',
    'invoiceIds',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "additionalInformation" => 1,
        "metaData" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("purchases"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function estimate($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "exemptionDetails" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("purchases","estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>