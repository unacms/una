<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerRelationship extends Model
{
  protected $allowed = [
    'parentId',
    'paymentOwnerId',
    'invoiceOwnerId',
  ];

}

?>