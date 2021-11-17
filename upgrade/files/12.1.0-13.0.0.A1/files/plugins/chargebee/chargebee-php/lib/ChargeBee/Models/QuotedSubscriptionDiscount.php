<?php

class ChargeBee_QuotedSubscriptionDiscount extends ChargeBee_Model
{
  protected $allowed = array('id', 'invoice_name', 'type', 'percentage', 'amount', 'currency_code', 'duration_type', 'period', 'period_unit', 'included_in_mrr', 'apply_on', 'item_price_id', 'created_at', 'apply_till', 'applied_count');

}

?>