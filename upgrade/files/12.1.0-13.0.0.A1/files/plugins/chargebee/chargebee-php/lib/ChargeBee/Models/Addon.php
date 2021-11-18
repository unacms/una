<?php

class ChargeBee_Addon extends ChargeBee_Model
{

  protected $allowed = array('id', 'name', 'invoiceName', 'description', 'pricingModel', 'type', 'chargeType',
'price', 'currencyCode', 'period', 'periodUnit', 'unit', 'status', 'archivedAt', 'enabledInPortal','taxCode', 'taxjarProductCode', 'avalaraSaleType', 'avalaraTransactionType', 'avalaraServiceType','sku', 'accountingCode', 'accountingCategory1', 'accountingCategory2', 'accountingCategory3','accountingCategory4', 'isShippable', 'shippingFrequencyPeriod', 'shippingFrequencyPeriodUnit','resourceVersion', 'updatedAt', 'priceInDecimal', 'includedInMrr', 'invoiceNotes', 'taxable','taxProfileId', 'metaData', 'tiers', 'showDescriptionInInvoices', 'showDescriptionInQuotes');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("addons"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("addons",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("addons"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("addons",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("addons",$id,"delete"), array(), $env, $headers);
  }

  public static function copy($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("addons","copy"), $params, $env, $headers);
  }

  public static function unarchive($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("addons",$id,"unarchive"), array(), $env, $headers);
  }

 }

?>