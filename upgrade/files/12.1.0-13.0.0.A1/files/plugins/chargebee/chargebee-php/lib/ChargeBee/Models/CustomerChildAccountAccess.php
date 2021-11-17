<?php

class ChargeBee_CustomerChildAccountAccess extends ChargeBee_Model
{
  protected $allowed = array('portal_edit_subscriptions', 'portal_download_invoices', 'send_subscription_emails', 'send_invoice_emails', 'send_payment_emails');

}

?>