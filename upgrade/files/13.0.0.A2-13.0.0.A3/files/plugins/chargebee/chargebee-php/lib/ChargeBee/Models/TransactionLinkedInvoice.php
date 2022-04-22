<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class TransactionLinkedInvoice extends Model
{
  protected $allowed = [
    'invoiceId',
    'appliedAmount',
    'appliedAt',
    'invoiceDate',
    'invoiceTotal',
    'invoiceStatus',
  ];

}

?>