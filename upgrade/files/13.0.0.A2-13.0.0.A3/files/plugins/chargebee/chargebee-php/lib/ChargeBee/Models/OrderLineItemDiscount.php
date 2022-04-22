<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OrderLineItemDiscount extends Model
{
  protected $allowed = [
    'lineItemId',
    'discountType',
    'couponId',
    'entityId',
    'discountAmount',
  ];

}

?>