<?php

namespace ChargeBee\ChargeBee\Exceptions;

use ChargeBee\ChargeBee\Exceptions\APIError;

class InvalidRequestException extends APIError
{
    public function __construct($httpStatusCode, $jsonObject)
    {
        parent::__construct($httpStatusCode, $jsonObject);
    }
}
