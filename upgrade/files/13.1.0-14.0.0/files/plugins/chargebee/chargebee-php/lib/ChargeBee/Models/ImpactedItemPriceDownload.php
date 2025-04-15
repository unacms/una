<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ImpactedItemPriceDownload extends Model
{
  protected $allowed = [
    'downloadUrl',
    'validTill',
    'mimeType',
  ];

}

?>