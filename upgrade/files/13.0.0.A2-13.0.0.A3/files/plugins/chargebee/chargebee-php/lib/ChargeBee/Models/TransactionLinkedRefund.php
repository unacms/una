<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class TransactionLinkedRefund extends Model
{
  protected $allowed = [
    'txnId',
    'txnStatus',
    'txnDate',
    'txnAmount',
  ];

}

?>