<?php

class ChargeBee_QuoteLineItemTax extends ChargeBee_Model
{
  protected $allowed = array('line_item_id', 'tax_name', 'tax_rate', 'is_partial_tax_applied', 'is_non_compliance_tax', 'taxable_amount', 'tax_amount', 'tax_juris_type', 'tax_juris_name', 'tax_juris_code', 'tax_amount_in_local_currency', 'local_currency_code');

}

?>