<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CreditNoteAllocation extends Model
{
  protected $allowed = [
    'invoiceId',
    'allocatedAmount',
    'allocatedAt',
    'invoiceDate',
    'invoiceStatus',
  ];

}

?>