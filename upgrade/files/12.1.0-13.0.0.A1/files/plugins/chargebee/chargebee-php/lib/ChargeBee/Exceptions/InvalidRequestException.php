<?php
class ChargeBee_InvalidRequestException extends ChargeBee_APIError
{
	function __construct($httpStatusCode,$jsonObject)
	{
		parent::__construct($httpStatusCode,$jsonObject);
    }
}
?>
