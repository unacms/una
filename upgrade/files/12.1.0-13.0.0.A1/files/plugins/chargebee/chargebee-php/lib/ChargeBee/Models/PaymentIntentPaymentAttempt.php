<?php

class ChargeBee_PaymentIntentPaymentAttempt extends ChargeBee_Model
{
  protected $allowed = array('id', 'status', 'payment_method_type', 'id_at_gateway', 'error_code', 'error_text', 'created_at', 'modified_at');

}

?>