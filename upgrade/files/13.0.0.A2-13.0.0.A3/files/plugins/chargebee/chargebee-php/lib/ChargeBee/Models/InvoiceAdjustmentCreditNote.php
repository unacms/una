<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceAdjustmentCreditNote extends Model
{
  protected $allowed = [
    'cnId',
    'cnReasonCode',
    'cnCreateReasonCode',
    'cnDate',
    'cnTotal',
    'cnStatus',
  ];

}

?>