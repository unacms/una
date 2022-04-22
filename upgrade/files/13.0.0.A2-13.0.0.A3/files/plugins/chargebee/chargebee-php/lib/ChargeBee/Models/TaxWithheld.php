<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class TaxWithheld extends Model
{

  protected $allowed = [
    'id',
    'user',
    'referenceNumber',
    'description',
    'type',
    'paymentMethod',
    'date',
    'currencyCode',
    'amount',
    'exchangeRate',
  ];



  # OPERATIONS
  #-----------

 }

?>