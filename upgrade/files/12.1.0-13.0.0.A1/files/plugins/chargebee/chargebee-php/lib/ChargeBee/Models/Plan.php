<?php

class ChargeBee_Plan extends ChargeBee_Model
{

  protected $allowed = array('id', 'name', 'invoiceName', 'description', 'price', 'currencyCode', 'period',
'periodUnit', 'trialPeriod', 'trialPeriodUnit', 'trialEndAction', 'pricingModel', 'chargeModel','freeQuantity', 'setupCost', 'downgradePenalty', 'status', 'archivedAt', 'billingCycles', 'redirectUrl','enabledInHostedPages', 'enabledInPortal', 'addonApplicability', 'taxCode', 'taxjarProductCode','avalaraSaleType', 'avalaraTransactionType', 'avalaraServiceType', 'sku', 'accountingCode','accountingCategory1', 'accountingCategory2', 'accountingCategory3', 'accountingCategory4','isShippable', 'shippingFrequencyPeriod', 'shippingFrequencyPeriodUnit', 'resourceVersion','updatedAt', 'giftable', 'claimUrl', 'freeQuantityInDecimal', 'priceInDecimal', 'invoiceNotes','taxable', 'taxProfileId', 'metaData', 'tiers', 'applicableAddons', 'attachedAddons', 'eventBasedAddons','showDescriptionInInvoices', 'showDescriptionInQuotes');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("plans"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("plans",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans",$id,"delete"), array(), $env, $headers);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans","copy"), $params, $env, $headers);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans",$id,"unarchive"), array(), $env, $headers);
  }

 }

?>