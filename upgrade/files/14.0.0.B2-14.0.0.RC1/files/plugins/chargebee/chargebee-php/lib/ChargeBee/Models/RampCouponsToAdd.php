<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class RampCouponsToAdd extends Model
{
  protected $allowed = [
    'couponId',
    'applyTill',
  ];

}

?>