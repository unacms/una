<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionEventBasedAddon extends Model
{
  protected $allowed = [
    'id',
    'quantity',
    'unitPrice',
    'servicePeriodInDays',
    'onEvent',
    'chargeOnce',
    'quantityInDecimal',
    'unitPriceInDecimal',
  ];

}

?>