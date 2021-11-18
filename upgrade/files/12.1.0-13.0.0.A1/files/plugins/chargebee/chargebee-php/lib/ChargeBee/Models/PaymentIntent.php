<?php

class ChargeBee_PaymentIntent extends ChargeBee_Model
{

  protected $allowed = array('id', 'status', 'currencyCode', 'amount', 'gatewayAccountId', 'expiresAt', 'referenceId',
'paymentMethodType', 'successUrl', 'failureUrl', 'createdAt', 'modifiedAt', 'resourceVersion','updatedAt', 'customerId', 'gateway', 'activePaymentAttempt');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("payment_intents"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("payment_intents",$id), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("payment_intents",$id), array(), $env, $headers);
  }

 }

?>