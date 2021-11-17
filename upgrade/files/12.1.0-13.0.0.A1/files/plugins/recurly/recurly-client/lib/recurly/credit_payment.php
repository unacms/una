<?php
/**
 * class Recurly_CreditPayment
 * @property string $uuid The uuid of the credit payment.
 * @property string $currency The currency of the amount_in_cents.
 * @property int $amount_in_cents The amount of the credit payment.
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $voided_at
 */
class Recurly_CreditPayment extends Recurly_Resource
{
  protected function getNodeName() {
    return 'credit_payment';
  }

  protected function getWriteableAttributes() {
   return array();
  }
}
