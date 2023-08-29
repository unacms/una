<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CouponCouponConstraint extends Model
{
  protected $allowed = [
    'entityType',
    'type',
    'value',
  ];

}

?>