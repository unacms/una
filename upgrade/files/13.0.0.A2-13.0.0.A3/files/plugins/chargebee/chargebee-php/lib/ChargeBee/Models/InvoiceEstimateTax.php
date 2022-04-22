<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceEstimateTax extends Model
{
  protected $allowed = [
    'name',
    'amount',
    'description',
  ];

}

?>