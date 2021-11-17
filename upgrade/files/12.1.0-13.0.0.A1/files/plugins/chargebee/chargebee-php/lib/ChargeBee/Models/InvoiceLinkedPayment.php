<?php

class ChargeBee_InvoiceLinkedPayment extends ChargeBee_Model
{
  protected $allowed = array('txn_id', 'applied_amount', 'applied_at', 'txn_status', 'txn_date', 'txn_amount');

}

?>