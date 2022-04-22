<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedChargeAddon extends Model
{
  protected $allowed = [
    'id',
    'quantity',
    'unitPrice',
    'quantityInDecimal',
    'unitPriceInDecimal',
    'servicePeriod',
  ];

}

?>