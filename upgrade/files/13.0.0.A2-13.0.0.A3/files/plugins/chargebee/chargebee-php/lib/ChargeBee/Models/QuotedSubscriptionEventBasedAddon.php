<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedSubscriptionEventBasedAddon extends Model
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