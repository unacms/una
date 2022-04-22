<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuoteTax extends Model
{
  protected $allowed = [
    'name',
    'amount',
    'description',
  ];

}

?>