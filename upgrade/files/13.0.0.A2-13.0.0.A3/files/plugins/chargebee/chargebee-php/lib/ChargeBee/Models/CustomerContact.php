<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerContact extends Model
{
  protected $allowed = [
    'id',
    'firstName',
    'lastName',
    'email',
    'phone',
    'label',
    'enabled',
    'sendAccountEmail',
    'sendBillingEmail',
  ];

}

?>