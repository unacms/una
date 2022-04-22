<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionChargedEventBasedAddon extends Model
{
  protected $allowed = [
    'id',
    'lastChargedAt',
  ];

}

?>