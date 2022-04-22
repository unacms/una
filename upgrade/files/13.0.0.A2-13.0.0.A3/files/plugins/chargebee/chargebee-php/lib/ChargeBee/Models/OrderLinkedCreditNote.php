<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OrderLinkedCreditNote extends Model
{
  protected $allowed = [
    'amount',
    'type',
    'id',
    'status',
    'amountAdjusted',
    'amountRefunded',
  ];

}

?>