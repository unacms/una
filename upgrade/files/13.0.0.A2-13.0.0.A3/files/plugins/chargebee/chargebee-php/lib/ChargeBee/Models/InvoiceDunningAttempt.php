<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceDunningAttempt extends Model
{
  protected $allowed = [
    'attempt',
    'transactionId',
    'dunningType',
    'createdAt',
    'txnStatus',
    'txnAmount',
  ];

}

?>