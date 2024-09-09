<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerTaxProvidersField extends Model
{
  protected $allowed = [
    'providerName',
    'fieldId',
    'fieldValue',
  ];

}

?>