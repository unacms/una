<?php

class ChargeBee_CustomerParentAccountAccess extends ChargeBee_Model
{
  protected $allowed = array('portal_edit_child_subscriptions', 'portal_download_child_invoices', 'send_subscription_emails', 'send_invoice_emails', 'send_payment_emails');

}

?>