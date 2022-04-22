<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedChargeCharge extends Model
{
  protected $allowed = [
    'amount',
    'amountInDecimal',
    'description',
    'servicePeriodInDays',
    'avalaraSaleType',
    'avalaraTransactionType',
    'avalaraServiceType',
  ];

}

?>