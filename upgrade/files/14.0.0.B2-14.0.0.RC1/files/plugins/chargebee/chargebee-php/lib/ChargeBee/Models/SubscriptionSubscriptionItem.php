<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionSubscriptionItem extends Model
{
  protected $allowed = [
    'itemPriceId',
    'itemType',
    'quantity',
    'quantityInDecimal',
    'meteredQuantity',
    'lastCalculatedAt',
    'unitPrice',
    'unitPriceInDecimal',
    'amount',
    'amountInDecimal',
    'billingPeriod',
    'billingPeriodUnit',
    'freeQuantity',
    'freeQuantityInDecimal',
    'trialEnd',
    'billingCycles',
    'servicePeriodDays',
    'chargeOnEvent',
    'chargeOnce',
    'chargeOnOption',
    'prorationType',
  ];

}

?>