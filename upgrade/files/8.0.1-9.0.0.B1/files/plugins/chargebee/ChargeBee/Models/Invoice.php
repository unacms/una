<?php

class ChargeBee_Invoice extends ChargeBee_Model
{

  protected $allowed = array('id', 'poNumber', 'customerId', 'subscriptionId', 'recurring', 'status', 'vatNumber',
'priceType', 'date', 'total', 'amountPaid', 'amountAdjusted', 'writeOffAmount', 'creditsApplied','amountDue', 'paidAt', 'dunningStatus', 'nextRetryAt', 'subTotal', 'tax', 'firstInvoice', 'currencyCode','lineItems', 'discounts', 'taxes', 'lineItemTaxes', 'linkedPayments', 'appliedCredits', 'adjustmentCreditNotes','issuedCreditNotes', 'linkedOrders', 'notes', 'shippingAddress', 'billingAddress');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices"), $params, $env, $headers);
  }

  public static function charge($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices","charge"), $params, $env, $headers);
  }

  public static function chargeAddon($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices","charge_addon"), $params, $env, $headers);
  }

  public static function stopDunning($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"stop_dunning"), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("invoices"), $params, $env, $headers);
  }

  public static function invoicesForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("customers",$id,"invoices"), $params, $env, $headers);
  }

  public static function invoicesForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("subscriptions",$id,"invoices"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("invoices",$id), array(), $env, $headers);
  }

  public static function pdf($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"pdf"), array(), $env, $headers);
  }

  public static function addCharge($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"add_charge"), $params, $env, $headers);
  }

  public static function addAddonCharge($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"add_addon_charge"), $params, $env, $headers);
  }

  public static function close($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"close"), array(), $env, $headers);
  }

  public static function collectPayment($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"collect_payment"), $params, $env, $headers);
  }

  public static function recordPayment($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"record_payment"), $params, $env, $headers);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"refund"), $params, $env, $headers);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"record_refund"), $params, $env, $headers);
  }

  public static function voidInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"void"), $params, $env, $headers);
  }

  public static function delete($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("invoices",$id,"delete"), $params, $env, $headers);
  }

 }

?>