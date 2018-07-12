<?php

class ChargeBee_PaymentSourceBankAccount extends ChargeBee_Model
{
  protected $allowed = array('last4', 'name_on_account', 'bank_name', 'mandate_id', 'account_type', 'echeck_type', 'account_holder_type');

}

?>