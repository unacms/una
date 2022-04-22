<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CreditNoteLinkedRefund extends Model
{
  protected $allowed = [
    'txnId',
    'appliedAmount',
    'appliedAt',
    'txnStatus',
    'txnDate',
    'txnAmount',
    'refundReasonCode',
  ];

}

?>