<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceEstimateLineItemTier extends Model
{
  protected $allowed = [
    'lineItemId',
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