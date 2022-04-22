<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionChargedItem extends Model
{
  protected $allowed = [
    'itemPriceId',
    'lastChargedAt',
  ];

}

?>