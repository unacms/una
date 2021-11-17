<?php

class ChargeBee_CustomerContact extends ChargeBee_Model
{
  protected $allowed = array('id', 'first_name', 'last_name', 'email', 'phone', 'label', 'enabled', 'send_account_email', 'send_billing_email');

}

?>