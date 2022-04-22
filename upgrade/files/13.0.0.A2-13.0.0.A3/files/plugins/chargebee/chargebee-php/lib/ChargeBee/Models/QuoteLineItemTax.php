<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuoteLineItemTax extends Model
{
  protected $allowed = [
    'lineItemId',
    'taxName',
    'taxRate',
    'isPartialTaxApplied',
    'isNonComplianceTax',
    'taxableAmount',
    'taxAmount',
    'taxJurisType',
    'taxJurisName',
    'taxJurisCode',
    'taxAmountInLocalCurrency',
    'localCurrencyCode',
  ];

}

?>