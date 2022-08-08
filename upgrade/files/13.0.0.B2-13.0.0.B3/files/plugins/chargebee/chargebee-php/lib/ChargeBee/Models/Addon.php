<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Addon extends Model
{

  protected $allowed = [
    'id',
    'name',
    'invoiceName',
    'description',
    'pricingModel',
    'type',
    'chargeType',
    'price',
    'currencyCode',
    'period',
    'periodUnit',
    'unit',
    'status',
    'archivedAt',
    'enabledInPortal',
    'taxCode',
    'hsnCode',
    'taxjarProductCode',
    'avalaraSaleType',
    'avalaraTransactionType',
    'avalaraServiceType',
    'sku',
    'accountingCode',
    'accountingCategory1',
    'accountingCategory2',
    'accountingCategory3',
    'accountingCategory4',
    'isShippable',
    'shippingFrequencyPeriod',
    'shippingFrequencyPeriodUnit',
    'resourceVersion',
    'updatedAt',
    'priceInDecimal',
    'includedInMrr',
    'channel',
    'invoiceNotes',
    'taxable',
    'taxProfileId',
    'metaData',
    'tiers',
    'showDescriptionInInvoices',
    'showDescriptionInQuotes',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("addons"), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("addons",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("addons"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("addons",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("addons",$id,"delete"), array(), $env, $headers);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("addons","copy"), $params, $env, $headers);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("addons",$id,"unarchive"), array(), $env, $headers);
  }

 }

?>