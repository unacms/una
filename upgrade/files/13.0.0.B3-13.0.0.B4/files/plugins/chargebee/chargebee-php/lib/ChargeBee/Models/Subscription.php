<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Subscription extends Model
{

  protected $allowed = [
    'id',
    'currencyCode',
    'planId',
    'planQuantity',
    'planUnitPrice',
    'setupFee',
    'billingPeriod',
    'billingPeriodUnit',
    'startDate',
    'trialEnd',
    'remainingBillingCycles',
    'poNumber',
    'autoCollection',
    'planQuantityInDecimal',
    'planUnitPriceInDecimal',
    'customerId',
    'planAmount',
    'planFreeQuantity',
    'status',
    'trialStart',
    'trialEndAction',
    'currentTermStart',
    'currentTermEnd',
    'nextBillingAt',
    'createdAt',
    'startedAt',
    'activatedAt',
    'giftId',
    'contractTermBillingCycleOnRenewal',
    'overrideRelationship',
    'pauseDate',
    'resumeDate',
    'cancelledAt',
    'cancelReason',
    'affiliateToken',
    'createdFromIp',
    'resourceVersion',
    'updatedAt',
    'hasScheduledAdvanceInvoices',
    'hasScheduledChanges',
    'paymentSourceId',
    'planFreeQuantityInDecimal',
    'planAmountInDecimal',
    'cancelScheduleCreatedAt',
    'offlinePaymentMethod',
    'channel',
    'netTermDays',
    'subscriptionItems',
    'itemTiers',
    'chargedItems',
    'dueInvoicesCount',
    'dueSince',
    'totalDues',
    'mrr',
    'exchangeRate',
    'baseCurrencyCode',
    'addons',
    'eventBasedAddons',
    'chargedEventBasedAddons',
    'coupon',
    'coupons',
    'shippingAddress',
    'referralInfo',
    'invoiceNotes',
    'metaData',
    'metadata',
    'deleted',
    'changesScheduledAt',
    'contractTerm',
    'cancelReasonCode',
    'freePeriod',
    'freePeriodUnit',
    'createPendingInvoices',
    'autoCloseInvoices',
    'discounts',
    'businessEntityId',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions"), $params, $env, $headers);
  }

  public static function createForCustomer($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"subscriptions"), $params, $env, $headers);
  }

  public static function createWithItems($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"subscription_for_items"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("subscriptions"), $params, $env, $headers);
  }

  public static function subscriptionsForCustomer($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("customers",$id,"subscriptions"), $params, $env, $headers);
  }

  public static function contractTermsForSubscription($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"contract_terms"), $params, $env, $headers);
  }

  public static function listDiscounts($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"discounts"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id), array(), $env, $headers);
  }

  public static function retrieveWithScheduledChanges($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"retrieve_with_scheduled_changes"), array(), $env, $headers);
  }

  public static function removeScheduledChanges($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_scheduled_changes"), array(), $env, $headers);
  }

  public static function removeScheduledCancellation($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_scheduled_cancellation"), $params, $env, $headers);
  }

  public static function removeCoupons($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_coupons"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id), $params, $env, $headers);
  }

  public static function updateForItems($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"update_for_items"), $params, $env, $headers);
  }

  public static function changeTermEnd($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"change_term_end"), $params, $env, $headers);
  }

  public static function reactivate($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"reactivate"), $params, $env, $headers);
  }

  public static function addChargeAtTermEnd($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"add_charge_at_term_end"), $params, $env, $headers);
  }

  public static function chargeAddonAtTermEnd($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"charge_addon_at_term_end"), $params, $env, $headers);
  }

  public static function chargeFutureRenewals($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"charge_future_renewals"), $params, $env, $headers);
  }

  public static function editAdvanceInvoiceSchedule($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"edit_advance_invoice_schedule"), $params, $env, $headers);
  }

  public static function retrieveAdvanceInvoiceSchedule($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("subscriptions",$id,"retrieve_advance_invoice_schedule"), array(), $env, $headers);
  }

  public static function removeAdvanceInvoiceSchedule($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_advance_invoice_schedule"), $params, $env, $headers);
  }

  public static function regenerateInvoice($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"regenerate_invoice"), $params, $env, $headers);
  }

  public static function importSubscription($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions","import_subscription"), $params, $env, $headers);
  }

  public static function importForCustomer($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"import_subscription"), $params, $env, $headers);
  }

  public static function importContractTerm($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"import_contract_term"), $params, $env, $headers);
  }

  public static function importForItems($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("customers",$id,"import_for_items"), $params, $env, $headers);
  }

  public static function overrideBillingProfile($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"override_billing_profile"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"delete"), array(), $env, $headers);
  }

  public static function pause($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"pause"), $params, $env, $headers);
  }

  public static function cancel($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"cancel"), $params, $env, $headers);
  }

  public static function cancelForItems($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"cancel_for_items"), $params, $env, $headers);
  }

  public static function resume($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"resume"), $params, $env, $headers);
  }

  public static function removeScheduledPause($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_scheduled_pause"), array(), $env, $headers);
  }

  public static function removeScheduledResumption($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("subscriptions",$id,"remove_scheduled_resumption"), array(), $env, $headers);
  }

 }

?>