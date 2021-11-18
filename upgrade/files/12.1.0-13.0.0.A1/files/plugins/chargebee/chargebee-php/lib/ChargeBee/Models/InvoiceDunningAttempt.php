<?php

class ChargeBee_InvoiceDunningAttempt extends ChargeBee_Model
{
  protected $allowed = array('attempt', 'transaction_id', 'dunning_type', 'created_at', 'txn_status', 'txn_amount');

}

?>