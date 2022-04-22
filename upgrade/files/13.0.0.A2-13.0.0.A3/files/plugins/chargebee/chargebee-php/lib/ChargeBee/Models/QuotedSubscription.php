<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class QuotedSubscription extends Model
{

  protected $allowed = [
    'id',
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
    'changesScheduledAt',
    'changeOption',
    'contractTermBillingCycleOnRenewal',
    'addons',
    'eventBasedAddons',
    'coupons',
    'subscriptionItems',
    'itemTiers',
    'quotedContractTerm',
  ];



  # OPERATIONS
  #-----------

 }

?>