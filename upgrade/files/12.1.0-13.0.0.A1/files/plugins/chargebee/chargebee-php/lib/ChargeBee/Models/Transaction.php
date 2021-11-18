<?php

class ChargeBee_Transaction extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'subscriptionId', 'gatewayAccountId', 'paymentSourceId', 'paymentMethod',
'referenceNumber', 'gateway', 'type', 'date', 'settledAt', 'exchangeRate', 'currencyCode', 'amount','idAtGateway', 'status', 'fraudFlag', 'initiatorType', 'threeDSecure', 'authorizationReason','errorCode', 'errorText', 'voidedAt', 'resourceVersion', 'updatedAt', 'fraudReason', 'amountUnused','maskedCardNumber', 'referenceTransactionId', 'refundedTxnId', 'referenceAuthorizationId', 'amountCapturable','reversalTransactionId', 'linkedInvoices', 'linkedCreditNotes', 'linkedRefunds', 'linkedPayments','deleted', 'merchantReferenceId');



  # OPERATIONS
  #-----------

  public static function createAuthorization($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("transactions","create_authorization"), $params, $env, $headers);
  }

  public static function voidTransaction($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("transactions",$id,"void"), array(), $env, $headers);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("transactions",$id,"record_refund"), $params, $env, $headers);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("transactions",$id,"refund"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("transactions"), $params, $env, $headers);
  }

  public static function transactionsForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("customers",$id,"transactions"), $params, $env, $headers);
  }

  public static function transactionsForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("subscriptions",$id,"transactions"), $params, $env, $headers);
  }

  public static function paymentsForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("invoices",$id,"payments"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("transactions",$id), array(), $env, $headers);
  }

  public static function deleteOfflineTransaction($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("transactions",$id,"delete_offline_transaction"), $params, $env, $headers);
  }

 }

?>