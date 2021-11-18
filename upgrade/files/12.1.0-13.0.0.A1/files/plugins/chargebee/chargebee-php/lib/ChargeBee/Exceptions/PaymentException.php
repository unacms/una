<?php
class ChargeBee_PaymentException extends ChargeBee_APIError
{

	function __construct($httpStatusCode,$jsonObject)
	{
		parent::__construct($httpStatusCode,$jsonObject);
    }
}
?>
