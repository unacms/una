<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class SubscriptionEstimate extends Model
{

  protected $allowed = [
    'id',
    'currencyCode',
    'status',
    'trialEndAction',
    'nextBillingAt',
    'pauseDate',
    'resumeDate',
    'shippingAddress',
    'contractTerm',
  ];



  # OPERATIONS
  #-----------

 }

?>