<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Order extends Model
{

  protected $allowed = [
    'id',
    'documentNumber',
    'invoiceId',
    'subscriptionId',
    'customerId',
    'status',
    'cancellationReason',
    'paymentStatus',
    'orderType',
    'priceType',
    'referenceId',
    'fulfillmentStatus',
    'orderDate',
    'shippingDate',
    'note',
    'trackingId',
    'trackingUrl',
    'batchId',
    'createdBy',
    'shipmentCarrier',
    'invoiceRoundOffAmount',
    'tax',
    'amountPaid',
    'amountAdjusted',
    'refundableCreditsIssued',
    'refundableCredits',
    'roundingAdjustement',
    'paidOn',
    'shippingCutOffDate',
    'createdAt',
    'statusUpdateAt',
    'deliveredAt',
    'shippedAt',
    'resourceVersion',
    'updatedAt',
    'cancelledAt',
    'resentStatus',
    'isResent',
    'originalOrderId',
    'orderLineItems',
    'shippingAddress',
    'billingAddress',
    'discount',
    'subTotal',
    'total',
    'lineItemTaxes',
    'lineItemDiscounts',
    'linkedCreditNotes',
    'deleted',
    'currencyCode',
    'isGifted',
    'giftNote',
    'giftId',
    'resendReason',
    'resentOrders',
    'businessEntityId',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function importOrder($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders","import_order"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function assignOrderNumber($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"assign_order_number"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function cancel($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"cancel"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createRefundableCreditNote($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"create_refundable_credit_note"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function reopen($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"reopen"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("orders",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("orders"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function ordersForInvoice($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("invoices",$id,"orders"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function resend($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("orders",$id,"resend"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>