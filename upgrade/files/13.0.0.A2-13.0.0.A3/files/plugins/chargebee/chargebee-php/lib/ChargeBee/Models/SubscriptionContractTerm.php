<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionContractTerm extends Model
{
  protected $allowed = [
    'id',
    'status',
    'contractStart',
    'contractEnd',
    'billingCycle',
    'actionAtTermEnd',
    'totalContractValue',
    'cancellationCutoffPeriod',
    'createdAt',
    'subscriptionId',
    'remainingBillingCycles',
  ];

}

?>