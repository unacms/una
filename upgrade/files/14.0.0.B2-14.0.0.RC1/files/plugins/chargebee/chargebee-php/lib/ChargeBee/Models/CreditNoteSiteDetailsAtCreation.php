<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CreditNoteSiteDetailsAtCreation extends Model
{
  protected $allowed = [
    'timezone',
    'organizationAddress',
  ];

}

?>