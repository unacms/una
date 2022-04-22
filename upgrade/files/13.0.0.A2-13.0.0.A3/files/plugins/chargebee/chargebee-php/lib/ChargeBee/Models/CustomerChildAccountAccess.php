<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerChildAccountAccess extends Model
{
  protected $allowed = [
    'portalEditSubscriptions',
    'portalDownloadInvoices',
    'sendSubscriptionEmails',
    'sendInvoiceEmails',
    'sendPaymentEmails',
  ];

}

?>