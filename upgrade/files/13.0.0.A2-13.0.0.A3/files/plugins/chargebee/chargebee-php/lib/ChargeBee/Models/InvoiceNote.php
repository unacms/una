<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceNote extends Model
{
  protected $allowed = [
    'entityType',
    'note',
    'entityId',
  ];

}

?>