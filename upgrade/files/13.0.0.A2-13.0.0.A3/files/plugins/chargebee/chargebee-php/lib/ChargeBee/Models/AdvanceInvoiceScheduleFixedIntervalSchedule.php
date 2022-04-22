<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class AdvanceInvoiceScheduleFixedIntervalSchedule extends Model
{
  protected $allowed = [
    'endScheduleOn',
    'numberOfOccurrences',
    'daysBeforeRenewal',
    'endDate',
    'createdAt',
    'termsToCharge',
  ];

}

?>