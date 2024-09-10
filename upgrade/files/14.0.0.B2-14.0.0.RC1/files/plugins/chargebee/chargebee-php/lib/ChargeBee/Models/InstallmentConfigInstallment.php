<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InstallmentConfigInstallment extends Model
{
  protected $allowed = [
    'period',
    'amountPercentage',
  ];

}

?>