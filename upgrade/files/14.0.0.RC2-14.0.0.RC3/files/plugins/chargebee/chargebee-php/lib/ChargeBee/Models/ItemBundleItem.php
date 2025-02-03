<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ItemBundleItem extends Model
{
  protected $allowed = [
    'itemId',
    'itemType',
    'quantity',
    'priceAllocation',
  ];

}

?>