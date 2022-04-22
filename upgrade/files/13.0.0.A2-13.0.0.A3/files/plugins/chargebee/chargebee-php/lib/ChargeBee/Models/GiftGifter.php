<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class GiftGifter extends Model
{
  protected $allowed = [
    'customerId',
    'invoiceId',
    'signature',
    'note',
  ];

}

?>