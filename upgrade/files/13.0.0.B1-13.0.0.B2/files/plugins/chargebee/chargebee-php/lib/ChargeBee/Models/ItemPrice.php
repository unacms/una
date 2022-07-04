<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class ItemPrice extends Model
{

  protected $allowed = [
    'id',
    'name',
    'itemFamilyId',
    'itemId',
    'description',
    'status',
    'externalName',
    'pricingModel',
    'price',
    'priceInDecimal',
    'period',
    'currencyCode',
    'periodUnit',
    'trialPeriod',
    'trialPeriodUnit',
    'trialEndAction',
    'shippingPeriod',
    'shippingPeriodUnit',
    'billingCycles',
    'freeQuantity',
    'freeQuantityInDecimal',
    'channel',
    'resourceVersion',
    'updatedAt',
    'createdAt',
    'archivedAt',
    'invoiceNotes',
    'tiers',
    'isTaxable',
    'taxDetail',
    'accountingDetail',
    'metadata',
    'itemType',
    'archivable',
    'parentItemId',
    'showDescriptionInInvoices',
    'showDescriptionInQuotes',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("item_prices"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("item_prices",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("item_prices",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("item_prices"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("item_prices",$id,"delete"), array(), $env, $headers);
  }

  public static function findApplicableItems($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("item_prices",$id,"applicable_items"), $params, $env, $headers);
  }

  public static function findApplicableItemPrices($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("item_prices",$id,"applicable_item_prices"), $params, $env, $headers);
  }

 }

?>