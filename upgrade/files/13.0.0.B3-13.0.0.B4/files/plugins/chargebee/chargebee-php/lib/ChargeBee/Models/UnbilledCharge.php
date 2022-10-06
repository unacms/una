<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class UnbilledCharge extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'subscriptionId',
    'dateFrom',
    'dateTo',
    'unitAmount',
    'pricingModel',
    'quantity',
    'amount',
    'currencyCode',
    'discountAmount',
    'description',
    'entityType',
    'entityId',
    'isVoided',
    'voidedAt',
    'unitAmountInDecimal',
    'quantityInDecimal',
    'amountInDecimal',
    'updatedAt',
    'tiers',
    'deleted',
  ];



  # OPERATIONS
  #-----------

  public static function createUnbilledCharge($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("unbilled_charges","create"), $params, $env, $headers);
  }

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("unbilled_charges"), $params, $env, $headers);
  }

  public static function invoiceUnbilledCharges($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("unbilled_charges","invoice_unbilled_charges"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("unbilled_charges",$id,"delete"), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("unbilled_charges"), $params, $env, $headers);
  }

  public static function invoiceNowEstimate($params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("unbilled_charges","invoice_now_estimate"), $params, $env, $headers);
  }

 }

?>