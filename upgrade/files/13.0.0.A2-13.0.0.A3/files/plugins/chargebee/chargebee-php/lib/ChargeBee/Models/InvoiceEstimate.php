<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class InvoiceEstimate extends Model
{

  protected $allowed = [
    'recurring',
    'priceType',
    'currencyCode',
    'subTotal',
    'total',
    'creditsApplied',
    'amountPaid',
    'amountDue',
    'lineItems',
    'discounts',
    'taxes',
    'lineItemTaxes',
    'lineItemTiers',
    'lineItemDiscounts',
    'roundOffAmount',
    'customerId',
  ];



  # OPERATIONS
  #-----------

 }

?>