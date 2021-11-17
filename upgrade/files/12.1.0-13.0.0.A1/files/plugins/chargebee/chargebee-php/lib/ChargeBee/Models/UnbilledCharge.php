<?php

class ChargeBee_UnbilledCharge extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'subscriptionId', 'dateFrom', 'dateTo', 'unitAmount', 'pricingModel',
'quantity', 'amount', 'currencyCode', 'discountAmount', 'description', 'entityType', 'entityId','isVoided', 'voidedAt', 'unitAmountInDecimal', 'quantityInDecimal', 'amountInDecimal', 'tiers','deleted');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("unbilled_charges"), $params, $env, $headers);
  }

  public static function invoiceUnbilledCharges($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("unbilled_charges","invoice_unbilled_charges"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("unbilled_charges",$id,"delete"), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("unbilled_charges"), $params, $env, $headers);
  }

  public static function invoiceNowEstimate($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("unbilled_charges","invoice_now_estimate"), $params, $env, $headers);
  }

 }

?>