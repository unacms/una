<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Invoice extends Model
{

  protected $allowed = [
    'id',
    'poNumber',
    'customerId',
    'subscriptionId',
    'recurring',
    'status',
    'vatNumber',
    'priceType',
    'date',
    'dueDate',
    'netTermDays',
    'exchangeRate',
    'currencyCode',
    'total',
    'amountPaid',
    'amountAdjusted',
    'writeOffAmount',
    'creditsApplied',
    'amountDue',
    'paidAt',
    'dunningStatus',
    'nextRetryAt',
    'voidedAt',
    'resourceVersion',
    'updatedAt',
    'subTotal',
    'subTotalInLocalCurrency',
    'totalInLocalCurrency',
    'localCurrencyCode',
    'tax',
    'firstInvoice',
    'newSalesAmount',
    'hasAdvanceCharges',
    'termFinalized',
    'isGifted',
    'generatedAt',
    'expectedPaymentDate',
    'amountToCollect',
    'roundOffAmount',
    'lineItems',
    'discounts',
    'lineItemDiscounts',
    'taxes',
    'lineItemTaxes',
    'lineItemTiers',
    'linkedPayments',
    'dunningAttempts',
    'appliedCredits',
    'adjustmentCreditNotes',
    'issuedCreditNotes',
    'linkedOrders',
    'notes',
    'shippingAddress',
    'billingAddress',
    'einvoice',
    'paymentOwner',
    'voidReasonCode',
    'deleted',
    'vatNumberPrefix',
    'channel',
    'businessEntityId',
  ];



  # OPERATIONS
  #-----------

  public static function create($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices"), $params, $env, $headers);
  }

  public static function createForChargeItemsAndCharges($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices","create_for_charge_items_and_charges"), $params, $env, $headers);
  }

  public static function charge($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices","charge"), $params, $env, $headers);
  }

  public static function chargeAddon($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices","charge_addon"), $params, $env, $headers);
  }

  public static function createForChargeItem($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices","create_for_charge_item"), $params, $env, $headers);
  }

  public static function stopDunning($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"stop_dunning"), $params, $env, $headers);
  }

  public static function importInvoice($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices","import_invoice"), $params, $env, $headers);
  }

  public static function applyPayments($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"apply_payments"), $params, $env, $headers);
  }

  public static function syncUsages($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"sync_usages"), array(), $env, $headers);
  }

  public static function applyCredits($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"apply_credits"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("invoices"), $params, $env, $headers);
  }

  public static function invoicesForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"invoices"), $params, $env, $headers);
  }

  public static function invoicesForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"invoices"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id), array(), $env, $headers);
  }

  public static function pdf($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"pdf"), $params, $env, $headers);
  }

  public static function downloadEinvoice($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"download_einvoice"), array(), $env, $headers);
  }

  public static function addCharge($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"add_charge"), $params, $env, $headers);
  }

  public static function addAddonCharge($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"add_addon_charge"), $params, $env, $headers);
  }

  public static function addChargeItem($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"add_charge_item"), $params, $env, $headers);
  }

  public static function close($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"close"), $params, $env, $headers);
  }

  public static function collectPayment($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"collect_payment"), $params, $env, $headers);
  }

  public static function recordPayment($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"record_payment"), $params, $env, $headers);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"refund"), $params, $env, $headers);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"record_refund"), $params, $env, $headers);
  }

  public static function removePayment($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"remove_payment"), $params, $env, $headers);
  }

  public static function removeCreditNote($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"remove_credit_note"), $params, $env, $headers);
  }

  public static function voidInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"void"), $params, $env, $headers);
  }

  public static function writeOff($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"write_off"), $params, $env, $headers);
  }

  public static function delete($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"delete"), $params, $env, $headers);
  }

  public static function updateDetails($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"update_details"), $params, $env, $headers);
  }

  public static function resendEinvoice($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("invoices",$id,"resend_einvoice"), array(), $env, $headers);
  }

 }

?>