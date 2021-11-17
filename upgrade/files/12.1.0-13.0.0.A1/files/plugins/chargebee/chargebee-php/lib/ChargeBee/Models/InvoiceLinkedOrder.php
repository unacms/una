<?php

class ChargeBee_InvoiceLinkedOrder extends ChargeBee_Model
{
  protected $allowed = array('id', 'document_number', 'status', 'order_type', 'reference_id', 'fulfillment_status', 'batch_id', 'created_at');

}

?>