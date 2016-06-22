<?php

class ChargeBee_CreditNote extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'subscriptionId', 'referenceInvoiceId', 'type', 'reasonCode',
'status', 'vatNumber', 'date', 'priceType', 'total', 'amountAllocated', 'amountRefunded', 'amountAvailable','refundedAt', 'subTotal', 'currencyCode', 'lineItems', 'discounts', 'taxes', 'lineItemTaxes','linkedRefunds', 'allocations');



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("credit_notes",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("credit_notes"), $params, $env, $headers);
  }

  public static function creditNotesForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("customers",$id,"credit_notes"), $params, $env, $headers);
  }

 }

?>