<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceEinvoice extends Model
{
  protected $allowed = [
    'id',
    'status',
    'message',
  ];

}

?>