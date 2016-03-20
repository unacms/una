<?php

class ChargeBee_InvoiceLinkedTransaction extends ChargeBee_Model
{
  protected $allowed = array('txn_id', 'applied_amount', 'txn_type', 'txn_status', 'txn_date', 'txn_amount');

}

?>