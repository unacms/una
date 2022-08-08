<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PaymentIntent extends Model
{

  protected $allowed = [
    'id',
    'status',
    'currencyCode',
    'amount',
    'gatewayAccountId',
    'expiresAt',
    'referenceId',
    'paymentMethodType',
    'successUrl',
    'failureUrl',
    'createdAt',
    'modifiedAt',
    'resourceVersion',
    'updatedAt',
    'customerId',
    'gateway',
    'activePaymentAttempt',
    'businessEntityId',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("payment_intents"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("payment_intents",$id), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("payment_intents",$id), array(), $env, $headers);
  }

 }

?>