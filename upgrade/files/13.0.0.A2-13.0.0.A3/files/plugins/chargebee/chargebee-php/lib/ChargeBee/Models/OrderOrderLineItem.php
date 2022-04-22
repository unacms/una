<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;

class OrderOrderLineItem extends Model
{
  protected $allowed = [
    'id',
    'invoiceId',
    'invoiceLineItemId',
    'unitPrice',
    'description',
    'amount',
    'fulfillmentQuantity',
    'fulfillmentAmount',
    'taxAmount',
    'amountPaid',
    'amountAdjusted',
    'refundableCreditsIssued',
    'refundableCredits',
    'isShippable',
    'sku',
    'status',
    'entityType',
    'itemLevelDiscountAmount',
    'discountAmount',
    'entityId',
  ];

}

?>