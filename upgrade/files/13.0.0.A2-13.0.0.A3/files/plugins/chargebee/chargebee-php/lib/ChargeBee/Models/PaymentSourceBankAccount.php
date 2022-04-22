<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PaymentSourceBankAccount extends Model
{
  protected $allowed = [
    'last4',
    'nameOnAccount',
    'firstName',
    'lastName',
    'bankName',
    'mandateId',
    'accountType',
    'echeckType',
    'accountHolderType',
    'email',
  ];

}

?>