<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InstallmentDetailInstallment extends Model
{
  protected $allowed = [
    'id',
    'invoiceId',
    'date',
    'amount',
    'status',
    'createdAt',
    'resourceVersion',
    'updatedAt',
  ];

}

?>