<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourceAmazonPayment extends Model
{
  protected $allowed = [
    'email',
    'agreementId',
  ];

}

?>