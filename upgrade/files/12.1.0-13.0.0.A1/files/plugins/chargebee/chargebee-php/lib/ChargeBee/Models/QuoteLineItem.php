<?php

class ChargeBee_QuoteLineItem extends ChargeBee_Model
{
  protected $allowed = array('id', 'subscription_id', 'date_from', 'date_to', 'unit_amount', 'quantity', 'amount', 'pricing_model', 'is_taxed', 'tax_amount', 'tax_rate', 'unit_amount_in_decimal', 'quantity_in_decimal', 'amount_in_decimal', 'discount_amount', 'item_level_discount_amount', 'description', 'entity_description', 'entity_type', 'tax_exempt_reason', 'entity_id', 'customer_id');

}

?>