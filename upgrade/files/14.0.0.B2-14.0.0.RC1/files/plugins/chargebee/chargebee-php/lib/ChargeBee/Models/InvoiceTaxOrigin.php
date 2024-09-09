<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceTaxOrigin extends Model
{
  protected $allowed = [
    'country',
    'registrationNumber',
  ];

}

?>