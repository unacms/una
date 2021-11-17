<?php
class ChargeBee_OperationFailedException extends ChargeBee_APIError
{
	function __construct($httpStatusCode,$jsonObject)
	{
		parent::__construct($httpStatusCode,$jsonObject);
    }
}
?>
