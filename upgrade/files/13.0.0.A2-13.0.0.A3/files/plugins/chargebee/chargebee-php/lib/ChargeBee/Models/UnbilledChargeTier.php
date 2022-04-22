<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class UnbilledChargeTier extends Model
{
  protected $allowed = [
    'startingUnit',
    'endingUnit',
    'quantityUsed',
    'unitAmount',
    'startingUnitInDecimal',
    'endingUnitInDecimal',
    'quantityUsedInDecimal',
    'unitAmountInDecimal',
  ];

}

?>