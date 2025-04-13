<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class CreditNote extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'subscriptionId',
    'referenceInvoiceId',
    'type',
    'reasonCode',
    'status',
    'vatNumber',
    'date',
    'priceType',
    'currencyCode',
    'total',
    'amountAllocated',
    'amountRefunded',
    'amountAvailable',
    'refundedAt',
    'voidedAt',
    'generatedAt',
    'resourceVersion',
    'updatedAt',
    'channel',
    'einvoice',
    'subTotal',
    'subTotalInLocalCurrency',
    'totalInLocalCurrency',
    'localCurrencyCode',
    'roundOffAmount',
    'fractionalCorrection',
    'lineItems',
    'discounts',
    'lineItemDiscounts',
    'lineItemTiers',
    'taxes',
    'lineItemTaxes',
    'linkedRefunds',
    'allocations',
    'deleted',
    'taxCategory',
    'localCurrencyExchangeRate',
    'createReasonCode',
    'vatNumberPrefix',
    'businessEntityId',
    'shippingAddress',
    'billingAddress',
    'siteDetailsAtCreation',
    'taxOrigin',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("credit_notes",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function pdf($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"pdf"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function downloadEinvoice($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("credit_notes",$id,"download_einvoice"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"refund"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"record_refund"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function voidCreditNote($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"void"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("credit_notes"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function creditNotesForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"credit_notes"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"delete"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function removeTaxWithheldRefund($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"remove_tax_withheld_refund"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function resendEinvoice($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"resend_einvoice"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function sendEinvoice($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes",$id,"send_einvoice"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function importCreditNote($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("credit_notes","import_credit_note"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>