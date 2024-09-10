<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ItemPriceTaxProvidersField extends Model
{
  protected $allowed = [
    'providerName',
    'fieldId',
    'fieldValue',
  ];

}

?>