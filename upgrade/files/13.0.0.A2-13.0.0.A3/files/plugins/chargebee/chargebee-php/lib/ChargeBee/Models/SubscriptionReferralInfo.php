<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class SubscriptionReferralInfo extends Model
{
  protected $allowed = [
    'referralCode',
    'couponCode',
    'referrerId',
    'externalReferenceId',
    'rewardStatus',
    'referralSystem',
    'accountId',
    'campaignId',
    'externalCampaignId',
    'friendOfferType',
    'referrerRewardType',
    'notifyReferralSystem',
    'destinationUrl',
    'postPurchaseWidgetEnabled',
  ];

}

?>