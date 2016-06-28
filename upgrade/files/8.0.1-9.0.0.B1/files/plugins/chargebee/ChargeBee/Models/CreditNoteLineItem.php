<?php

class ChargeBee_CreditNoteLineItem extends ChargeBee_Model
{
  protected $allowed = array('id', 'date_from', 'date_to', 'unit_amount', 'quantity', 'is_taxed', 'tax_amount', 'tax_rate', 'amount', 'discount_amount', 'item_level_discount_amount', 'description', 'entity_type', 'entity_id');

}

?>