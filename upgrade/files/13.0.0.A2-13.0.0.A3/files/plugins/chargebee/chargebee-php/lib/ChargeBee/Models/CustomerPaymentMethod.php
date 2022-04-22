<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerPaymentMethod extends Model
{
  protected $allowed = [
    'type',
    'gateway',
    'gatewayAccountId',
    'status',
    'referenceId',
  ];

}

?>