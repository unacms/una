<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceLinkedOrder extends Model
{
  protected $allowed = [
    'id',
    'documentNumber',
    'status',
    'orderType',
    'referenceId',
    'fulfillmentStatus',
    'batchId',
    'createdAt',
  ];

}

?>