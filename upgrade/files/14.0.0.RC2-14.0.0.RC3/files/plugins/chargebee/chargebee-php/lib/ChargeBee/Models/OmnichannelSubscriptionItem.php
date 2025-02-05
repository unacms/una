<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class OmnichannelSubscriptionItem extends Model
{

  protected $allowed = [
    'id',
    'itemIdAtSource',
    'status',
    'currentTermStart',
    'currentTermEnd',
    'expiredAt',
    'expirationReason',
    'cancelledAt',
    'cancellationReason',
    'resourceVersion',
  ];



  # OPERATIONS
  #-----------

 }

?>