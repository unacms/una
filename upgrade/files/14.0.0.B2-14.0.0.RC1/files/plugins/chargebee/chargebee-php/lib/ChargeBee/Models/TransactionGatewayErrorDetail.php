<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class TransactionGatewayErrorDetail extends Model
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

}

?>