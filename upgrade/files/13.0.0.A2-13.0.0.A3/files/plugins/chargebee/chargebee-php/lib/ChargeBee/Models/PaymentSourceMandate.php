<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourceMandate extends Model
{
  protected $allowed = [
    'id',
    'subscriptionId',
    'createdAt',
  ];

}

?>