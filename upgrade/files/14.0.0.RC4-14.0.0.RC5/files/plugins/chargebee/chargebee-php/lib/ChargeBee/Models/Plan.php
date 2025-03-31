<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Plan extends Model
{

  protected $allowed = [
    'id',
    'name',
    'invoiceName',
    'description',
    'price',
    'currencyCode',
    'period',
    'periodUnit',
    'trialPeriod',
    'trialPeriodUnit',
    'trialEndAction',
    'pricingModel',
    'chargeModel',
    'freeQuantity',
    'setupCost',
    'downgradePenalty',
    'status',
    'archivedAt',
    'billingCycles',
    'redirectUrl',
    'enabledInHostedPages',
    'enabledInPortal',
    'addonApplicability',
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
    'giftable',
    'claimUrl',
    'freeQuantityInDecimal',
    'priceInDecimal',
    'channel',
    'invoiceNotes',
    'taxable',
    'taxProfileId',
    'metaData',
    'tiers',
    'taxProvidersFields',
    'applicableAddons',
    'attachedAddons',
    'eventBasedAddons',
    'showDescriptionInInvoices',
    'showDescriptionInQuotes',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("plans"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metaData" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("plans",$id), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("plans"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("plans",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("plans",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("plans","copy"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("plans",$id,"unarchive"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>