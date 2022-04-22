<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CouponItemConstraintCriteria extends Model
{
  protected $allowed = [
    'itemType',
    'currencies',
    'itemFamilyIds',
    'itemPricePeriods',
  ];

}

?>