<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CreditNoteTax extends Model
{
  protected $allowed = [
    'name',
    'amount',
    'description',
  ];

}

?>