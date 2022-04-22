<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;
use ChargeBee\ChargeBee\Environment;

class Export extends Model
{

  protected $allowed = [
    'id',
    'operationType',
    'mimeType',
    'status',
    'createdAt',
    'download',
  ];

public function waitForExportCompletion($env = null, $headers = array()) {
  $count = 0;
  while($this->status == "in_process") {
     if( $count++ > 50) {
        throw new RuntimeException("Export is taking too long");
     }
     sleep(Environment::$exportWaitInSecs);
     $this->_values = self::retrieve($this->id, $env, $headers)->export()->getValues();
     $this->_load();
  }
  return $this;
}


  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("exports",$id), array(), $env, $headers);
  }

  public static function revenueRecognition($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","revenue_recognition"), $params, $env, $headers);
  }

  public static function deferredRevenue($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","deferred_revenue"), $params, $env, $headers);
  }

  public static function plans($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","plans"), $params, $env, $headers);
  }

  public static function addons($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","addons"), $params, $env, $headers);
  }

  public static function coupons($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","coupons"), $params, $env, $headers);
  }

  public static function customers($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","customers"), $params, $env, $headers);
  }

  public static function subscriptions($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","subscriptions"), $params, $env, $headers);
  }

  public static function invoices($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","invoices"), $params, $env, $headers);
  }

  public static function creditNotes($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","credit_notes"), $params, $env, $headers);
  }

  public static function transactions($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","transactions"), $params, $env, $headers);
  }

  public static function orders($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","orders"), $params, $env, $headers);
  }

  public static function itemFamilies($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","item_families"), $params, $env, $headers);
  }

  public static function items($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","items"), $params, $env, $headers);
  }

  public static function itemPrices($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","item_prices"), $params, $env, $headers);
  }

  public static function attachedItems($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","attached_items"), $params, $env, $headers);
  }

  public static function differentialPrices($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("exports","differential_prices"), $params, $env, $headers);
  }

 }

?>