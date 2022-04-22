<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class ExportDownload extends Model
{
  protected $allowed = [
    'downloadUrl',
    'validTill',
    'mimeType',
  ];

}

?>