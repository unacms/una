<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class CustomerReferralUrl extends Model
{
  protected $allowed = [
    'externalCustomerId',
    'referralSharingUrl',
    'createdAt',
    'updatedAt',
    'referralCampaignId',
    'referralAccountId',
    'referralExternalCampaignId',
    'referralSystem',
  ];

}

?>