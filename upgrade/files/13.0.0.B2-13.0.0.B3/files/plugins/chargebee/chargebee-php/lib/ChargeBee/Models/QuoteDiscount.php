<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuoteDiscount extends Model
{
  protected $allowed = [
    'amount',
    'description',
    'entityType',
    'entityId',
    'couponSetCode',
  ];

}

?>