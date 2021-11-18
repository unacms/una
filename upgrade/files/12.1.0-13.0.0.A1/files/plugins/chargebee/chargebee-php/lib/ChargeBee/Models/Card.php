<?php

class ChargeBee_Card extends ChargeBee_Model
{

  protected $allowed = array('paymentSourceId', 'status', 'gateway', 'gatewayAccountId', 'refTxId', 'firstName',
'lastName', 'iin', 'last4', 'cardType', 'fundingType', 'expiryMonth', 'expiryYear', 'issuingCountry','billingAddr1', 'billingAddr2', 'billingCity', 'billingStateCode', 'billingState', 'billingCountry','billingZip', 'createdAt', 'resourceVersion', 'updatedAt', 'ipAddress', 'poweredBy', 'customerId','maskedNumber');



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("cards",$id), array(), $env, $headers);
  }

  public static function updateCardForCustomer($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("customers",$id,"credit_card"), $params, $env, $headers);
  }

  public static function switchGatewayForCustomer($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("customers",$id,"switch_gateway"), $params, $env, $headers);
  }

  public static function copyCardForCustomer($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("customers",$id,"copy_card"), $params, $env, $headers);
  }

  public static function deleteCardForCustomer($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("customers",$id,"delete_card"), array(), $env, $headers);
  }

 }

?>