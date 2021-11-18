<?php

class ChargeBee_CreditNote extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'subscriptionId', 'referenceInvoiceId', 'type', 'reasonCode',
'status', 'vatNumber', 'date', 'priceType', 'currencyCode', 'total', 'amountAllocated', 'amountRefunded','amountAvailable', 'refundedAt', 'voidedAt', 'resourceVersion', 'updatedAt', 'subTotal', 'subTotalInLocalCurrency','totalInLocalCurrency', 'localCurrencyCode', 'roundOffAmount', 'fractionalCorrection', 'lineItems','discounts', 'lineItemDiscounts', 'lineItemTiers', 'taxes', 'lineItemTaxes', 'linkedRefunds','allocations', 'deleted', 'createReasonCode', 'vatNumberPrefix');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("credit_notes",$id), array(), $env, $headers);
  }

  public static function pdf($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes",$id,"pdf"), $params, $env, $headers);
  }

  public static function refund($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes",$id,"refund"), $params, $env, $headers);
  }

  public static function recordRefund($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes",$id,"record_refund"), $params, $env, $headers);
  }

  public static function voidCreditNote($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes",$id,"void"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("credit_notes"), $params, $env, $headers);
  }

  public static function creditNotesForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("customers",$id,"credit_notes"), $params, $env, $headers);
  }

  public static function delete($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("credit_notes",$id,"delete"), $params, $env, $headers);
  }

 }

?>