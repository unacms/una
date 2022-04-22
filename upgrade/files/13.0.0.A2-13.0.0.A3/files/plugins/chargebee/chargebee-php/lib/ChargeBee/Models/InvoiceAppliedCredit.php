<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceAppliedCredit extends Model
{
  protected $allowed = [
    'cnId',
    'appliedAmount',
    'appliedAt',
    'cnReasonCode',
    'cnCreateReasonCode',
    'cnDate',
    'cnStatus',
  ];

}

?>