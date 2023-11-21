<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PaymentVoucher extends Model
{

  protected $allowed = [
    'id',
    'idAtGateway',
    'paymentVoucherType',
    'expiresAt',
    'status',
    'subscriptionId',
    'currencyCode',
    'amount',
    'gatewayAccountId',
    'paymentSourceId',
    'gateway',
    'payload',
    'errorCode',
    'errorText',
    'url',
    'date',
    'resourceVersion',
    'updatedAt',
    'customerId',
    'linkedInvoices',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("payment_vouchers"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("payment_vouchers",$id), array(), $env, $headers);
  }

  public static function payment_vouchersForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"payment_vouchers"), $params, $env, $headers);
  }

  public static function payment_vouchersForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"payment_vouchers"), $params, $env, $headers);
  }

 }

?>