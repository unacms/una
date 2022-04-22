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
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons"), $params, $env, $headers);
  }

  public static function createForItems($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons","create_for_items"), $params, $env, $headers);
  }

  public static function updateForItems($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"update_for_items"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("coupons"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("coupons",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"delete"), array(), $env, $headers);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons","copy"), $params, $env, $headers);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("coupons",$id,"unarchive"), array(), $env, $headers);
  }

 }

?>