<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Coupon extends Model
{

  protected $allowed = [
    'id',
    'name',
    'invoiceName',
    'discountType',
    'discountPercentage',
    'discountAmount',
    'discountQuantity',
    'currencyCode',
    'durationType',
    'durationMonth',
    'validFrom',
    'validTill',
    'maxRedemptions',
    'status',
    'applyDiscountOn',
    'applyOn',
    'planConstraint',
    'addonConstraint',
    'createdAt',
    'archivedAt',
    'resourceVersion',
    'updatedAt',
    'includedInMrr',
    'period',
    'periodUnit',
    'planIds',
    'addonIds',
    'itemConstraints',
    'itemConstraintCriteria',
    'redemptions',
    'invoiceNotes',
    'metaData',
    'couponConstraints',
    'deleted',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createForItems($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
        "itemPriceIds" => 1,
        "itemFamilyIds" => 1,
        "currencies" => 1,
        "itemPricePeriods" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons","create_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateForItems($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
        "itemPriceIds" => 1,
        "itemFamilyIds" => 1,
        "currencies" => 1,
        "itemPricePeriods" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"update_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("coupons"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("coupons",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons","copy"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"unarchive"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>