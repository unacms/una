<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OmnichannelSubscriptionOmnichannelTransaction extends Model
{
  protected $allowed = [
    'id',
    'idAtSource',
    'appId',
    'priceCurrency',
    'priceUnits',
    'priceNanos',
    'type',
    'transactedAt',
    'createdAt',
    'resourceVersion',
  ];

}

?>