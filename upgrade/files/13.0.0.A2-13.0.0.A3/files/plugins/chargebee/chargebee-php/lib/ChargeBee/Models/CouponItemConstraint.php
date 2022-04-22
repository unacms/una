<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CouponItemConstraint extends Model
{
  protected $allowed = [
    'itemType',
    'constraint',
    'itemPriceIds',
  ];

}

?>