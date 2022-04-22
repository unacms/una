<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceTax extends Model
{
  protected $allowed = [
    'name',
    'amount',
    'description',
  ];

}

?>