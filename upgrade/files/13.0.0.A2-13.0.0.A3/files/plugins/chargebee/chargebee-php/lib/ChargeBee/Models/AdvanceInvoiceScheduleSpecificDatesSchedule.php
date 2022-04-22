<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class AdvanceInvoiceScheduleSpecificDatesSchedule extends Model
{
  protected $allowed = [
    'termsToCharge',
    'date',
    'createdAt',
  ];

}

?>