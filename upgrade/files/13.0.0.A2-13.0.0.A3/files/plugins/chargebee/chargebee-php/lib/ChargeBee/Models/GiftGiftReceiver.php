<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class GiftGiftReceiver extends Model
{
  protected $allowed = [
    'customerId',
    'subscriptionId',
    'firstName',
    'lastName',
    'email',
  ];

}

?>