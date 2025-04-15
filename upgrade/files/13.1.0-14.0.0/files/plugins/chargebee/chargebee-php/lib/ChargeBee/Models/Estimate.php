<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Estimate extends Model
{

  protected $allowed = [
    'createdAt',
    'subscriptionEstimate',
    'subscriptionEstimates',
    'invoiceEstimate',
    'invoiceEstimates',
    'paymentScheduleEstimates',
    'nextInvoiceEstimate',
    'creditNoteEstimates',
    'unbilledChargeEstimates',
  ];



  # OPERATIONS
  #-----------

  public static function createSubscription($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "exemptionDetails" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","create_subscription"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createSubItemEstimate($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "exemptionDetails" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","create_subscription_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createSubForCustomerEstimate($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"create_subscription_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createSubItemForCustomerEstimate($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"create_subscription_for_items_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateSubscription($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","update_subscription"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function updateSubscriptionForItems($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","update_subscription_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function renewalEstimate($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"renewal_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function advanceInvoiceEstimate($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"advance_invoice_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function regenerateInvoiceEstimate($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"regenerate_invoice_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function upcomingInvoicesEstimate($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"upcoming_invoices_estimate"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function changeTermEnd($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"change_term_end_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function cancelSubscription($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"cancel_subscription_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function cancelSubscriptionForItems($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"cancel_subscription_for_items_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function pauseSubscription($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"pause_subscription_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function resumeSubscription($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"resume_subscription_estimate"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function giftSubscription($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "additionalInformation" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","gift_subscription"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function giftSubscriptionForItems($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "additionalInformation" => 1,
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","gift_subscription_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createInvoice($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","create_invoice"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function createInvoiceForItems($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","create_invoice_for_items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function paymentSchedules($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("estimates","payment_schedules"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>