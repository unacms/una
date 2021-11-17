<?php

class ChargeBee_SubscriptionSubscriptionItem extends ChargeBee_Model
{
  protected $allowed = array('item_price_id', 'item_type', 'quantity', 'quantity_in_decimal', 'metered_quantity', 'last_calculated_at', 'unit_price', 'unit_price_in_decimal', 'amount', 'amount_in_decimal', 'free_quantity', 'free_quantity_in_decimal', 'trial_end', 'billing_cycles', 'service_period_days', 'charge_on_event', 'charge_once', 'charge_on_option');

}

?>