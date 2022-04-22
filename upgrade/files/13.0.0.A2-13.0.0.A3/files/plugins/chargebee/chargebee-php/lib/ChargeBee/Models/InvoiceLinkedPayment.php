<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceLinkedPayment extends Model
{
  protected $allowed = [
    'txnId',
    'appliedAmount',
    'appliedAt',
    'txnStatus',
    'txnDate',
    'txnAmount',
  ];

}

?>