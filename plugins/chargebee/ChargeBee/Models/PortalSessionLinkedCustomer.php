<?php

class ChargeBee_PortalSessionLinkedCustomer extends ChargeBee_Model
{
  protected $allowed = array('customer_id', 'email', 'has_billing_address', 'has_payment_method', 'has_active_subscription');

}

?>