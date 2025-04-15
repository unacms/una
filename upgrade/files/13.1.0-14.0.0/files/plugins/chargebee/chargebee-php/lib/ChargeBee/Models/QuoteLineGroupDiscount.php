<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuoteLineGroupDiscount extends Model
{
  protected $allowed = [
    'amount',
    'description',
    'entityType',
    'discountType',
    'entityId',
    'couponSetCode',
  ];

}

?>