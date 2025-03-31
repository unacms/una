<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Transaction extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'subscriptionId',
    'gatewayAccountId',
    'paymentSourceId',
    'paymentMethod',
    'referenceNumber',
    'gateway',
    'type',
    'date',
    'settledAt',
    'exchangeRate',
    'currencyCode',
    'amount',
    'idAtGateway',
    'status',
    'fraudFlag',
    'initiatorType',
    'threeDSecure',
    'authorizationReason',
    'errorCode',
    'errorText',
    'voidedAt',
    'resourceVersion',
    'updatedAt',
    'fraudReason',
    'customPaymentMethodId',
    'amountUnused',
    'maskedCardNumber',
    'referenceTransactionId',
    'refundedTxnId',
    'referenceAuthorizationId',
    'amountCapturable',
    'reversalTransactionId',
    'linkedInvoices',
    'linkedCreditNotes',
    'linkedRefunds',
    'linkedPayments',
    'deleted',
    'iin',
    'last4',
    'merchantReferenceId',
    'businessEntityId',
    'paymentMethodDetails',
    'errorDetail',
    'customPaymentMethodName',
  ];



  # OPERATIONS
  #-----------

  public static function createAuthorization($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions","create_authorization"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function voidTransaction($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"void"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"record_refund"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function reconcile($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"reconcile"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"refund"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("transactions"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function transactionsForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"transactions"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function transactionsForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"transactions"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function paymentsForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"payments"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("transactions",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function deleteOfflineTransaction($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"delete_offline_transaction"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>