<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentScheduleEstimateScheduleEntry extends Model
{
  protected $allowed = [
    'id',
    'date',
    'amount',
    'status',
  ];

}

?>