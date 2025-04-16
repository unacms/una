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
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("payment_vouchers"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("payment_vouchers",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function paymentVouchersForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"payment_vouchers"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function paymentVouchersForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"payment_vouchers"), $params, $env, $headers, null, false, $jsonKeys);
  }

  /**
  * @deprecated use paymentVouchersForInvoice instead
  */
  public static function payment_vouchersForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"payment_vouchers"), $params, $env, $headers);
  }
  /**
  * @deprecated use paymentVouchersForCustomer instead
  */
  public static function payment_vouchersForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"payment_vouchers"), $params, $env, $headers);
  }

 }

?>