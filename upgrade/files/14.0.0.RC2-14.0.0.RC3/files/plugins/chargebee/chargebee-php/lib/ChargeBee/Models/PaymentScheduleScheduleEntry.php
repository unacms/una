<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentScheduleScheduleEntry extends Model
{
  protected $allowed = [
    'id',
    'date',
    'amount',
    'status',
  ];

}

?>