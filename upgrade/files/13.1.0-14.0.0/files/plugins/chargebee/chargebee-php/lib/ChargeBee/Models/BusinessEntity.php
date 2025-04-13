<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class BusinessEntity extends Model
{

  protected $allowed = [
    'id',
    'name',
    'status',
    'deleted',
    'createdAt',
    'resourceVersion',
    'updatedAt',
  ];



  # OPERATIONS
  #-----------

  public static function createTransfers($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("business_entities","transfers"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function getTransfers($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("business_entities","transfers"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>