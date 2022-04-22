<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourcePaypal extends Model
{
  protected $allowed = [
    'email',
    'agreementId',
  ];

}

?>