<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class PortalSessionLinkedCustomer extends Model
{
  protected $allowed = [
    'customerId',
    'email',
    'hasBillingAddress',
    'hasPaymentMethod',
    'hasActiveSubscription',
  ];

}

?>