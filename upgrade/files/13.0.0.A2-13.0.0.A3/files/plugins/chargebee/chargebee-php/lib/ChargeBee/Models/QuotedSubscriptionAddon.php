<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedSubscriptionAddon extends Model
{
  protected $allowed = [
    'id',
    'quantity',
    'unitPrice',
    'amount',
    'trialEnd',
    'remainingBillingCycles',
    'quantityInDecimal',
    'unitPriceInDecimal',
    'amountInDecimal',
  ];

}

?>