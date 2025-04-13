<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class InvoiceSiteDetailsAtCreation extends Model
{
  protected $allowed = [
    'timezone',
    'organizationAddress',
  ];

}

?>