<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class ContractTerm extends Model
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



  # OPERATIONS
  #-----------

 }

?>