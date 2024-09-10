<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class QuoteLineGroupLineItemTax extends Model
{
  protected $allowed = [
    'lineItemId',
    'taxName',
    'taxRate',
    'dateTo',
    'dateFrom',
    'proratedTaxableAmount',
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