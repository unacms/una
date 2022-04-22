<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class QuoteLineGroup extends Model
{

  protected $allowed = [
    'version',
    'id',
    'subTotal',
    'total',
    'creditsApplied',
    'amountPaid',
    'amountDue',
    'chargeEvent',
    'billingCycleNumber',
    'lineItems',
    'discounts',
    'lineItemDiscounts',
    'taxes',
    'lineItemTaxes',
  ];



  # OPERATIONS
  #-----------

 }

?>