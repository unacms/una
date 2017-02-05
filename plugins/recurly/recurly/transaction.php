<?php
/**
 * Class Recurly_Transaction
 * @property string $uuid Transaction's unique identifier.
 * @property Recurly_Stub $account The URL of the account associated with the transaction.  Run get() to pull back a Recurly_Account
 * @property Recurly_Stub $invoice The URL of the invoice associated with the transaction.  Run get() to pull back a Recurly_Invoice
 * @property Recurly_Stub $subscription The URL of the subscription associated with the transaction.  Run get() to pull back a Recurly_Subscription
 * @property string $original_transaction For refund transactions, the URL of the original transaction.  Run get() to pull back a Recurly_Transaction
 * @property string $action purchase, verify or refund.
 * @property integer $amount_in_cents Total transaction amount in cents.
 * @property integer $tax_in_cents Amount of tax or VAT within the transaction, in cents.
 * @property string $currency 3-letter currency for the transaction.
 * @property string $status success, declined, or void.
 * @property string $payment_method credit_card, paypal, check, wire_transfer, money_order.
 * @property string $reference Transaction reference from your payment gateway.
 * @property string $source Source of the transaction. Possible values: transaction for one-time transactions, subscription for subscriptions, billing_info for updating billing info.
 * @property boolean $recurring True if transaction is recurring.
 * @property boolean $test True if test transaction.
 * @property boolean $voidable True if the transaction may be voidable, accuracy depends on your gateway.
 * @property string $refundable True if the transaction may be refunded.
 * @property string $ip_address Customer's IP address on the transaction, if applicable.
 * @property string $cvv_result CVV result, if applicable.
 * @property string $avs_result AVS result, if applicable.
 * @property string $avs_result_street AVS result for the street address, line 1.
 * @property string $avs_result_postal AVS result for the postal code.
 * @property DateTime $created_at Date the transaction took place.
 * @property DateTime $updated_at Date the transaction was last modified.
 * @property mixed[] $details Nested account and billing information submitted at the time of the transaction. When writing a client library, do not map these directly to Account or Billing Info objects.  Retrieve data by accessing the array details[0]->fieldname i.e. details[0]->email 
 * @property string $error_code For declined transactions, the error code (if applicable).
 * @property string $error_category For declined transactions, the error category (if applicable).
 * @property string $merchant_message For declined transactions, the message displayed to the merchant (if applicable).
 * @property string $customer_message For declined transactions, the message displayed to the customer (if applicable).
 * @property string $gateway_error_code For declined transactions, this field lists the gateway error code sent to us from the gateway (if applicable).
 */
class Recurly_Transaction extends Recurly_Resource
{
 /**
  * Get Tranasction by uuid
  * 
  * @param string $uuid
  * @param Recurly_Client $client optional
  * @return Recurly_Transaction
  */
  public static function get($uuid, $client = null) {
    return Recurly_Base::_get(Recurly_Transaction::uriForTransaction($uuid), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_TRANSACTIONS);
  }

  /**
   * Refund a previous, successful transaction
   */
  public function refund($amountInCents = null) {
    $uri = $this->uri();
    if (!is_null($amountInCents)) {
      $uri .= '?amount_in_cents=' . strval(intval($amountInCents));
    }
    $this->_save(Recurly_Client::DELETE, $uri);
  }

  /**
   * Attempt a void, otherwise refund
   */
  public function void() {
    trigger_error('Deprecated method: void(). Use refund() instead.', E_USER_NOTICE);
    $this->refund();
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else if (!empty($this->uuid))
      return Recurly_Transaction::uriForTransaction($this->uuid);
    else
      throw new Recurly_Error('"uuid" is not supplied');
  }
  protected static function uriForTransaction($uuid) {
    return Recurly_Client::PATH_TRANSACTIONS . '/' . rawurlencode($uuid);
  }

  protected function getNodeName() {
    return 'transaction';
  }
  protected function getWriteableAttributes() {
    return array(
      'account', 'amount_in_cents', 'currency', 'description', 'accounting_code',
      'tax_exempt', 'tax_code'
    );
  }
}
