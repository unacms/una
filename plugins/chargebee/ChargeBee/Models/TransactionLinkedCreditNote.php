<?php

class ChargeBee_TransactionLinkedCreditNote extends ChargeBee_Model
{
  protected $allowed = array('cn_id', 'applied_amount', 'applied_at', 'cn_reason_code', 'cn_date', 'cn_total', 'cn_status', 'cn_reference_invoice_id');

}

?>