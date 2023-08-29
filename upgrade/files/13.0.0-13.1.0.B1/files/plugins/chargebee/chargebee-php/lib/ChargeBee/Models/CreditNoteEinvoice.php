<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CreditNoteEinvoice extends Model
{
  protected $allowed = [
    'id',
    'referenceNumber',
    'status',
    'message',
  ];

}

?>