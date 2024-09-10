<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class RampDiscountsToAdd extends Model
{
  protected $allowed = [
    'id',
    'invoiceName',
    'type',
    'percentage',
    'amount',
    'durationType',
    'period',
    'periodUnit',
    'includedInMrr',
    'applyOn',
    'itemPriceId',
    'createdAt',
  ];

}

?>