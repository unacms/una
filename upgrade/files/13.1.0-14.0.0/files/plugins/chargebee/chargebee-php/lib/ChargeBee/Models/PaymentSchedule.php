<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PaymentSchedule extends Model
{

  protected $allowed = [
    'id',
    'schemeId',
    'entityType',
    'entityId',
    'amount',
    'createdAt',
    'resourceVersion',
    'updatedAt',
    'currencyCode',
    'scheduleEntries',
  ];



  # OPERATIONS
  #-----------

 }

?>