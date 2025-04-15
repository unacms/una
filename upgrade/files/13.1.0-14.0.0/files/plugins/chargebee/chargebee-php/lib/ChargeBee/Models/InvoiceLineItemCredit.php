<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceLineItemCredit extends Model
{
  protected $allowed = [
    'cnId',
    'appliedAmount',
    'lineItemId',
  ];

}

?>