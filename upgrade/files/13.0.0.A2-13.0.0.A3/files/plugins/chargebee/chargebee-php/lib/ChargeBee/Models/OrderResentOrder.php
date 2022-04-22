<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OrderResentOrder extends Model
{
  protected $allowed = [
    'orderId',
    'reason',
    'amount',
  ];

}

?>