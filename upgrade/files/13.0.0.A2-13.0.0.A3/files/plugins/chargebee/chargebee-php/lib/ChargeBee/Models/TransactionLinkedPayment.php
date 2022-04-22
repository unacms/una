<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class TransactionLinkedPayment extends Model
{
  protected $allowed = [
    'id',
    'status',
    'amount',
    'date',
  ];

}

?>