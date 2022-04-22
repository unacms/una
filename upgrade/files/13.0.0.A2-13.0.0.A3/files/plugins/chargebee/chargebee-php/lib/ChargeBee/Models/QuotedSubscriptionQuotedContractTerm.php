<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedSubscriptionQuotedContractTerm extends Model
{
  protected $allowed = [
    'contractStart',
    'contractEnd',
    'billingCycle',
    'actionAtTermEnd',
    'totalContractValue',
    'cancellationCutoffPeriod',
  ];

}

?>