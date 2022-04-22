<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PlanEventBasedAddon extends Model
{
  protected $allowed = [
    'id',
    'quantity',
    'onEvent',
    'chargeOnce',
    'quantityInDecimal',
  ];

}

?>