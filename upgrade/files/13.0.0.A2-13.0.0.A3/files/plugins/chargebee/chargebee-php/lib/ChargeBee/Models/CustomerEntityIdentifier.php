<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerEntityIdentifier extends Model
{
  protected $allowed = [
    'id',
    'value',
    'scheme',
    'standard',
  ];

}

?>