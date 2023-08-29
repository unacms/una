<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourceCustVoucherSource extends Model
{
  protected $allowed = [
    'last4',
    'firstName',
    'lastName',
    'email',
  ];

}

?>