<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Configuration extends Model
{

  protected $allowed = [
    'domain',
    'productCatalogVersion',
  ];



  # OPERATIONS
  #-----------

  public static function all($env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("configurations"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>