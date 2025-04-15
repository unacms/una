<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceEstimateLineItemCredit extends Model
{
  protected $allowed = [
    'cnId',
    'appliedAmount',
    'lineItemId',
  ];

}

?>