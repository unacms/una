<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OrderShippingAddress extends Model
{
  protected $allowed = [
    'firstName',
    'lastName',
    'email',
    'company',
    'phone',
    'line1',
    'line2',
    'line3',
    'city',
    'stateCode',
    'state',
    'country',
    'zip',
    'validationStatus',
    'index',
  ];

}

?>