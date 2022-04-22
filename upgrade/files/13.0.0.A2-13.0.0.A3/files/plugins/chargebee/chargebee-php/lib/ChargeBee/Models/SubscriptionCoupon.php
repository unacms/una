<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionCoupon extends Model
{
  protected $allowed = [
    'couponId',
    'applyTill',
    'appliedCount',
    'couponCode',
  ];

}

?>