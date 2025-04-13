<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentScheduleSchemePreferredSchedule extends Model
{
  protected $allowed = [
    'period',
    'amountPercentage',
  ];

}

?>