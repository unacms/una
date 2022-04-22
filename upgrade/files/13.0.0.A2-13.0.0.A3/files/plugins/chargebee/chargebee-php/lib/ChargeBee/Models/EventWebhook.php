<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class EventWebhook extends Model
{
  protected $allowed = [
    'id',
    'webhookStatus',
  ];

}

?>