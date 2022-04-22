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
    'freeQuantity',
    'freeQuantityInDecimal',
    'trialEnd',
    'billingCycles',
    'servicePeriodDays',
    'chargeOnEvent',
    'chargeOnce',
    'chargeOnOption',
  ];

}

?>