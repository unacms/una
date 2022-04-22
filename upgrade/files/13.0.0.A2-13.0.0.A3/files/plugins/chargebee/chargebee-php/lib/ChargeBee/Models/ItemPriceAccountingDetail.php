<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ItemPriceAccountingDetail extends Model
{
  protected $allowed = [
    'sku',
    'accountingCode',
    'accountingCategory1',
    'accountingCategory2',
    'accountingCategory3',
    'accountingCategory4',
  ];

}

?>