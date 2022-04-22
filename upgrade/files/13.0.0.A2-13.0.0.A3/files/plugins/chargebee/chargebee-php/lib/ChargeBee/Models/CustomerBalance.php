<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerBalance extends Model
{
  protected $allowed = [
    'promotionalCredits',
    'excessPayments',
    'refundableCredits',
    'unbilledCharges',
    'currencyCode',
    'balanceCurrencyCode',
  ];

}

?>