<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ItemPriceTaxDetail extends Model
{
  protected $allowed = [
    'taxProfileId',
    'avalaraSaleType',
    'avalaraTransactionType',
    'avalaraServiceType',
    'avalaraTaxCode',
    'hsnCode',
    'taxjarProductCode',
  ];

}

?>