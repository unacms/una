<?php

class ChargeBee_QuotedSubscriptionQuotedContractTerm extends ChargeBee_Model
{
  protected $allowed = array('contract_start', 'contract_end', 'billing_cycle', 'action_at_term_end', 'total_contract_value', 'cancellation_cutoff_period');

}

?>