<?php
/**
 * class Recurly_Invoice
 * @property Recurly_Stub $account
 * @property Recurly_Stub $subscriptions
 * @property Recurly_Address $address
 * @property Recurly_ShippingAddress $shipping_address
 * @property string $uuid
 * @property string $state
 * @property int $invoice_number_prefix
 * @property int $invoice_number
 * @property string $po_number
 * @property string $vat_number
 * @property int $subtotal_in_cents The total of all adjustments on the invoice after discounts are applied, but before taxes.
 * @property int $discount_in_cents The total of all discounts applied to adjustments on the invoice.
 * @property DateTime $due_on If type = charge, will have a value that is the created_at plus the terms. If type = credit, will be null.
 * @property int $balance_in_cents The total_in_cents minus all successful transactions and credit payments for the invoice.
 * @property int $type Whether the invoice is a credit invoice or charge invoice.
 * @property int $origin The event that created the invoice.
 * @property Recurly_Stub $credit_invoices
 * @property int $refundable_total_in_cents
 * @property array $credit_payments
 * @property int $tax_in_cents
 * @property int $total_in_cents
 * @property string $currency
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $closed_at
 * @property string $terms_and_conditions
 * @property string $vat_reverse_charge_notes
 * @property string $customer_notes
 * @property string $tax_type
 * @property string $tax_region
 * @property float $tax_rate
 * @property int $net_terms
 * @property string $collection_method
 * @property Recurly_Stub $redemptions
 * @property Recurly_Adjustment[] $line_items
 * @property Recurly_TransactionList $transactions
 * @property string $all_transactions A link to all transactions on the invoice. Only present if there are more than 500 transactions
 * @property int $subtotal_before_discount_in_cents The total of all adjustments on the invoice before discounts or taxes are applied.
 * @property int $credit_customer_notes Allows merchant to set customer notes on a credit invoice. Will only be rejected if type is set to "charge", otherwise will be ignored if no credit invoice is created.
 */
class Recurly_Invoice extends Recurly_Resource
{
  /**
   * Lookup an invoice by its ID
   *
   * @param string Invoice number
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_Invoice
   * @throws Recurly_Error
   */
  public static function get($invoiceNumber, $client = null) {
    return self::_get(Recurly_Invoice::uriForInvoice($invoiceNumber), $client);
  }

  /**
   *
   * Retrieve the PDF version of this invoice
   *
   * @param string $locale
   * @return string A binary string of pdf data
   * @throws Recurly_Error
   */
  public function getPdf($locale = null) {
    return Recurly_Invoice::getInvoicePdf($this->invoiceNumberWithPrefix(), $locale, $this->_client);
  }

  /**
   * Retrieve the PDF version of an invoice
   *
   * @param $invoiceNumber
   * @param string $locale
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return string A binary string of pdf data
   * @throws Recurly_Error
   */
  public static function getInvoicePdf($invoiceNumber, $locale = null, $client = null) {
    $uri = self::uriForInvoice($invoiceNumber);

    if (is_null($client))
      $client = new Recurly_Client();

    return $client->getPdf($uri, $locale);
  }

  /**
   * Creates an invoice for an account using its pending charges
   *
   * @param string $accountCode Unique account code
   * @param array $attributes additional invoice attributes (see writeableAttributes)
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection collection of invoices on success
   * @throws Recurly_Error
   */
  public static function invoicePendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES;
    $invoice = new self();
    return Recurly_InvoiceCollection::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  /**
   * Previews an invoice for an account using its pending charges
   * @param string $accountCode Unique account code
   * @param array $attributes additional invoice attributes (see writeableAttributes)
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection collection of invoices on success
   * @throws Recurly_Error
   */
  public static function previewPendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES . '/preview';
    $invoice = new self();
    return Recurly_InvoiceCollection::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  /**
   * @throws Recurly_Error
   */
  public function markSuccessful() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/mark_successful');
  }

  /**
   * @throws Recurly_Error
   */
  public function forceCollect() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/collect');
  }

  /**
   * @throws Recurly_Error
   */
  public function void() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/void');
  }

  /**
   * @throws Recurly_Error
   */
  public function markFailed() {
    return Recurly_InvoiceCollection::_put($this->uri() . '/mark_failed', $this->_client);
  }

  public function invoiceNumberWithPrefix() {
    return $this->invoice_number_prefix . $this->invoice_number;
  }

  /**
   * Enters an offline payment for an invoice
   * @param $transaction Recurly_Transaction additional transaction attributes. The attributes available to set are (payment_method, collected_at, amount_in_cents, description)
   * @return object Recurly_Transaction transaction on success
   * @throws Recurly_Error
   */
  public function enterOfflinePayment($transaction) {
    $uri = $this->uri() . '/transactions';
    return Recurly_Transaction::_post($uri, $transaction->xml(), $this->_client);
  }

  /**
   * Refunds an open amount from the invoice and returns a collection of refund invoices
   * @param int $amount_in_cents amount in cents to refund from this invoice
   * @param string $refund_method indicates the refund order to apply, valid options: {'credit_first','transaction_first'}, defaults to 'credit_first'
   * @return object Recurly_Invoice a new refund invoice
   * @throws Recurly_Error
   */
  public function refundAmount($amount_in_cents, $refund_method = 'credit_first') {
    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('refund_method', $refund_method));
    $root->appendChild($doc->createElement('amount_in_cents', $amount_in_cents));

    return $this->createRefundInvoice($this->renderXML($doc));
  }

  /**
   *
   * Refunds given line items from an invoice and returns new refund invoice
   *
   * @param array $line_items refund attributes or Array of refund attributes to refund (see 'REFUND ATTRIBUTES' in docs or Recurly_Adjustment#toRefundAttributes)
   * @param string $refund_method indicates the refund order to apply, valid options: {'credit_first','transaction_first'}, defaults to 'credit_first'
   * @return object Recurly_Invoice a new refund invoice
   * @throws Recurly_Error
   */
  public function refund($line_items, $refund_method = 'credit_first') {
    if (isset($line_items['uuid'])) { $line_items = array($line_items); }

    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('refund_method', $refund_method));
    $line_items_node = $root->appendChild($doc->createElement('line_items'));

    foreach ($line_items as $line_item) {
      $adjustment_node = $line_items_node->appendChild($doc->createElement('adjustment'));
      $adjustment_node->appendChild($doc->createElement('uuid', $line_item['uuid']));
      $adjustment_node->appendChild($doc->createElement('quantity', $line_item['quantity']));
      $adjustment_node->appendChild($doc->createElement('prorate', $line_item['prorate'] ? 'true' : 'false'));
    }

    return $this->createRefundInvoice($this->renderXML($doc));
  }

  /**
   * Attempts to update an invoice
   *
   * @throws Recurly_Error
   */
  public function update() {
    $this->_save(Recurly_Client::PUT, $this->uri());
  }

  /**
   * @param string $xml_string
   * @return object Recurly_Invoice
   * @throws Recurly_Error
   */
  protected function createRefundInvoice($xml_string) {
    return Recurly_Invoice::_post($this->uri() . '/refund', $xml_string, $this->_client);
  }

  protected function getNodeName() {
    return 'invoice';
  }
  protected function getWriteableAttributes() {
    return array(
      'address', 'terms_and_conditions', 'customer_notes', 'vat_reverse_charge_notes',
      'collection_method', 'net_terms', 'po_number', 'currency', 'credit_customer_notes'
    );
  }

  /**
   * @return null|string
   * @throws Recurly_Error
   */
  protected function uri() {
    $invoiceNumberWithPrefix = $this->invoiceNumberWithPrefix();
    if (!empty($this->_href))
      return $this->getHref();
    else if (!empty($invoiceNumberWithPrefix))
      return Recurly_Invoice::uriForInvoice($invoiceNumberWithPrefix);
    else
      throw new Recurly_Error("Invoice number not specified");
  }
  protected static function uriForInvoice($invoiceNumber) {
    return Recurly_Client::PATH_INVOICES . '/' . rawurlencode($invoiceNumber);
  }
}
