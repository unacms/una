<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerParentAccountAccess extends Model
{
  protected $allowed = [
    'portalEditChildSubscriptions',
    'portalDownloadChildInvoices',
    'sendSubscriptionEmails',
    'sendInvoiceEmails',
    'sendPaymentEmails',
  ];

}

?>