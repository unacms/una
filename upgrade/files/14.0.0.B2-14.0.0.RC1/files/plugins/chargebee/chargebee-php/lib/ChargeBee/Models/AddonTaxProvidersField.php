<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class AddonTaxProvidersField extends Model
{
  protected $allowed = [
    'providerName',
    'fieldId',
    'fieldValue',
  ];

}

?>