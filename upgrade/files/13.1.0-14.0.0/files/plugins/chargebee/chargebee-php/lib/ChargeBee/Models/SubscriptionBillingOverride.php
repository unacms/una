<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionBillingOverride extends Model
{
  protected $allowed = [
    'maxExcessPaymentUsage',
    'maxRefundableCreditsUsage',
  ];

}

?>