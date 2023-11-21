<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentVoucherLinkedInvoice extends Model
{
  protected $allowed = [
    'invoiceId',
    'txnId',
    'appliedAt',
  ];

}

?>