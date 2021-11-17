<?php

class ChargeBee_Order extends ChargeBee_Model
{

  protected $allowed = array('id', 'documentNumber', 'invoiceId', 'subscriptionId', 'customerId', 'status',
'cancellationReason', 'paymentStatus', 'orderType', 'priceType', 'referenceId', 'fulfillmentStatus','orderDate', 'shippingDate', 'note', 'trackingId', 'trackingUrl', 'batchId', 'createdBy', 'shipmentCarrier','invoiceRoundOffAmount', 'tax', 'amountPaid', 'amountAdjusted', 'refundableCreditsIssued', 'refundableCredits','roundingAdjustement', 'paidOn', 'shippingCutOffDate', 'createdAt', 'statusUpdateAt', 'deliveredAt','shippedAt', 'resourceVersion', 'updatedAt', 'cancelledAt', 'resentStatus', 'isResent', 'originalOrderId','orderLineItems', 'shippingAddress', 'billingAddress', 'discount', 'subTotal', 'total', 'lineItemTaxes','lineItemDiscounts', 'linkedCreditNotes', 'deleted', 'currencyCode', 'isGifted', 'giftNote','giftId', 'resendReason', 'resentOrders');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id), $params, $env, $headers);
  }

  public static function importOrder($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders","import_order"), $params, $env, $headers);
  }

  public static function assignOrderNumber($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"assign_order_number"), array(), $env, $headers);
  }

  public static function cancel($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"cancel"), $params, $env, $headers);
  }

  public static function createRefundableCreditNote($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"create_refundable_credit_note"), $params, $env, $headers);
  }

  public static function reopen($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"reopen"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("orders",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"delete"), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("orders"), $params, $env, $headers);
  }

  public static function ordersForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("invoices",$id,"orders"), $params, $env, $headers);
  }

  public static function resend($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("orders",$id,"resend"), $params, $env, $headers);
  }

 }

?>