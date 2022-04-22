<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuotedChargeInvoiceItem extends Model
{
  protected $allowed = [
    'itemPriceId',
    'quantity',
    'quantityInDecimal',
    'unitPrice',
    'unitPriceInDecimal',
    'servicePeriodDays',
  ];

}

?>