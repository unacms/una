<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class TransactionLinkedCreditNote extends Model
{
  protected $allowed = [
    'cnId',
    'appliedAmount',
    'appliedAt',
    'cnReasonCode',
    'cnCreateReasonCode',
    'cnDate',
    'cnTotal',
    'cnStatus',
    'cnReferenceInvoiceId',
  ];

}

?>