<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class FeatureLevel extends Model
{
  protected $allowed = [
    'name',
    'value',
    'level',
    'isUnlimited',
  ];

}

?>