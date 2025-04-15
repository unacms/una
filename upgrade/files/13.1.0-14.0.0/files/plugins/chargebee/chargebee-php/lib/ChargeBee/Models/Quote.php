<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Quote extends Model
{

  protected $allowed = [
    'id',
    'name',
    'poNumber',
    'customerId',
    'subscriptionId',
    'invoiceId',
    'status',
    'operationType',
    'vatNumber',
    'priceType',
    'validTill',
    'date',
    'totalPayable',
    'chargeOnAcceptance',
    'subTotal',
    'total',
    'creditsApplied',
    'amountPaid',
    'amountDue',
    'version',
    'resourceVersion',
    'updatedAt',
    'vatNumberPrefix',
    'lineItems',
    'discounts',
    'lineItemDiscounts',
    'taxes',
    'lineItemTaxes',
    'lineItemTiers',
    'taxCategory',
    'currencyCode',
    'notes',
    'shippingAddress',
    'billingAddress',
    'contractTermStart',
    'contractTermEnd',
    'contractTermTerminationFee',
    'businessEntityId',
    'deleted',
  ];



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("quotes",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function createSubForCustomerQuote($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"create_subscription_quote"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editCreateSubForCustomerQuote($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_create_subscription_quote"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateSubscriptionQuote($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes","update_subscription_quote"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editUpdateSubscriptionQuote($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_update_subscription_quote"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createForOnetimeCharges($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes","create_for_onetime_charges"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editOneTimeQuote($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_one_time_quote"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createSubItemsForCustomerQuote($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"create_subscription_quote_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editCreateSubCustomerQuoteForItems($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_create_subscription_quote_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateSubscriptionQuoteForItems($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes","update_subscription_quote_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editUpdateSubscriptionQuoteForItems($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_update_subscription_quote_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createForChargeItemsAndCharges($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes","create_for_charge_items_and_charges"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function editForChargeItemsAndCharges($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"edit_for_charge_items_and_charges"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("quotes"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function quoteLineGroupsForQuote($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("quotes",$id,"quote_line_groups"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function convert($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"convert"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateStatus($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"update_status"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function extendExpiryDate($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"extend_expiry_date"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"delete"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function pdf($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("quotes",$id,"pdf"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>