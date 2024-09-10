<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class GatewayErrorDetail extends Model
{

  protected $allowed = [
    'requestId',
    'errorCategory',
    'errorCode',
    'errorMessage',
    'declineCode',
    'declineMessage',
    'networkErrorCode',
    'networkErrorMessage',
    'errorField',
    'recommendationCode',
    'recommendationMessage',
    'processorErrorCode',
    'processorErrorMessage',
  ];



  # OPERATIONS
  #-----------

 }

?>