<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourceCard extends Model
{
  protected $allowed = [
    'firstName',
    'lastName',
    'iin',
    'last4',
    'brand',
    'fundingType',
    'expiryMonth',
    'expiryYear',
    'billingAddr1',
    'billingAddr2',
    'billingCity',
    'billingStateCode',
    'billingState',
    'billingCountry',
    'billingZip',
    'maskedNumber',
  ];

}

?>