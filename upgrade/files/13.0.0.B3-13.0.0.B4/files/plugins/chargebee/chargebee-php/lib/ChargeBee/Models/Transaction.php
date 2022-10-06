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
  ];



  # OPERATIONS
  #-----------

  public static function createAuthorization($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("transactions","create_authorization"), $params, $env, $headers);
  }

  public static function voidTransaction($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"void"), array(), $env, $headers);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"record_refund"), $params, $env, $headers);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"refund"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("transactions"), $params, $env, $headers);
  }

  public static function transactionsForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"transactions"), $params, $env, $headers);
  }

  public static function transactionsForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"transactions"), $params, $env, $headers);
  }

  public static function paymentsForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"payments"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("transactions",$id), array(), $env, $headers);
  }

  public static function deleteOfflineTransaction($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("transactions",$id,"delete_offline_transaction"), $params, $env, $headers);
  }

 }

?>