<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Token extends Model
{

  protected $allowed = [
    'id',
    'gateway',
    'gatewayAccountId',
    'paymentMethodType',
    'status',
    'idAtVault',
    'vault',
    'ipAddress',
    'createdAt',
    'expiredAt',
  ];



  # OPERATIONS
  #-----------

 }

?>