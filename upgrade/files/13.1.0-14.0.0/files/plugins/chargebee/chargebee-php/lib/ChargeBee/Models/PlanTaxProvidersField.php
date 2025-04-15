<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PlanTaxProvidersField extends Model
{
  protected $allowed = [
    'providerName',
    'fieldId',
    'fieldValue',
  ];

}

?>