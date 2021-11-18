<?php

class ChargeBee_SubscriptionAddon extends ChargeBee_Model
{
  protected $allowed = array('id', 'quantity', 'unit_price', 'amount', 'trial_end', 'remaining_billing_cycles', 'quantity_in_decimal', 'unit_price_in_decimal', 'amount_in_decimal');

}

?>