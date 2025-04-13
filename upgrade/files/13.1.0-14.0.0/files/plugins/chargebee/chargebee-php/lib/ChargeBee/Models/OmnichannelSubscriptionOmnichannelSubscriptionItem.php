<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OmnichannelSubscriptionOmnichannelSubscriptionItem extends Model
{
  protected $allowed = [
    'id',
    'idAtSource',
    'status',
    'currentTermStart',
    'currentTermEnd',
    'expiredAt',
    'expirationReason',
    'cancelledAt',
    'cancellationReason',
  ];

}

?>