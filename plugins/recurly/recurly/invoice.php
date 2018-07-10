<?php
/**
 * class Recurly_Invoice
 * @property Recurly_Stub $account
 * @property Recurly_Address $address
 * @property Recurly_Stub $subscriptions
 * @property Recurly_String $all_transactions A link to all transactions on the invoice. Only present if there are more than 500 transactions
 * @property string $uuid
 * @property string $state
 * @property int $invoice_number
 * @property int $tax_in_cents
 * @property int $total_in_cents
 * @property DateTime $created_at
 * @property DateTime $closed_at
 * @property int $net_terms
 * @property string $collection_method
 * @property int $subtotal_before_discount_in_cents The total of all adjustments on the invoice before discounts or taxes are applied.
 * @property int $subtotal_in_cents The total of all adjustments on the invoice after discounts are applied, but before taxes.
 * @property int $discount_in_cents The total of all discounts applied to adjustments on the invoice.
 * @property int $balance_in_cents The total_in_cents minus all successful transactions and credit payments for the invoice.
 * @property DateTime $due_on If type = charge, will have a value that is the created_at plus the terms. If type = credit, will be null.
 * @property int $type Whether the invoice is a credit invoice or charge invoice.
 * @property int $origin The event that created the invoice.
 * @property int $credit_customer_notes Allows merchant to set customer notes on a credit invoice. Will only be rejected if type is set to "charge", otherwise will be ignored if no credit invoice is created.
 * @property Recurly_Adjustment[] $line_items
 * @property Recurly_TransactionList $transactions
 */
class Recurly_Invoice extends Recurly_Resource
{
  /**
   * Lookup an invoice by its ID
   * @param string Invoice number
   * @return Recurly_Invoice invoice
   */
  public static function get($invoiceNumber, $client = null) {
    return self::_get(Recurly_Invoice::uriForInvoice($invoiceNumber), $client);
  }

  /**
   * Retrieve the PDF version of this invoice
   */
  public function getPdf($locale = null) {
    return Recurly_Invoice::getInvoicePdf($this->invoiceNumberWithPrefix(), $locale, $this->_client);
  }

  /**
   * Retrieve the PDF version of an invoice
   */
  public static function getInvoicePdf($invoiceNumber, $locale = null, $client = null) {
    $uri = self::uriForInvoice($invoiceNumber);

    if (is_null($client))
      $client = new Recurly_Client();

    return $client->getPdf($uri, $locale);
  }

  /**
   * Creates an invoice for an account using its pending charges
   * @param string Unique account code
   * @param array additional invoice attributes (see writeableAttributes)
   * @return Recurly_InvoiceCollection collection of invoices on success
   **/
  public static function invoicePendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES;
    $invoice = new self();
    return Recurly_InvoiceCollection::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  /**
   * Previews an invoice for an account using its pending charges
   * @param string Unique account code
   * @param array additional invoice attributes (see writeableAttributes)
   * @return Recurly_InvoiceCollection collection of invoices on success
   */
  public static function previewPendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES . '/preview';
    $invoice = new self();
    return Recurly_InvoiceCollection::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  public function markSuccessful() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/mark_successful');
  }

  public function forceCollect() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/collect');
  }

  public function void() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/void');
  }

  public function markFailed() {
    return Recurly_InvoiceCollection::_put($this->uri() . '/mark_failed', $this->_client);
  }

  public function invoiceNumberWithPrefix() {
    return $this->invoice_number_prefix . $this->invoice_number;
  }

  /**
   * Enters an offline payment for an invoice
   * @param Recurly_Transaction additional transaction attributes. The attributes available to set are (payment_method, collected_at, amount_in_cents, description)
   * @return Recurly_Transaction transaction on success
   */
  public function enterOfflinePayment($transaction) {
    $uri = $this->uri() . '/transactions';
    return Recurly_Transaction::_post($uri, $transaction->xml(), $this->_client);
  }

  /**
   * Refunds an open amount from the invoice and returns a collection of refund invoices
   * @param Integer amount in cents to refund from this invoice
   * @param String indicates the refund order to apply, valid options: {'credit_first','transaction_first'}, defaults to 'credit_first'
   * @return Recurly_Invoice a new refund invoice
   */
  public function refundAmount($amount_in_cents, $refund_method = 'credit_first') {
    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('refund_method', $refund_method));
    $root->appendChild($doc->createElement('amount_in_cents', $amount_in_cents));

    return $this->createRefundInvoice($this->renderXML($doc));
  }

  /**
   * Refunds given line items from an invoice and returns new refund invoice
   * @param Array refund attributes or Array of refund attributes to refund (see 'REFUND ATTRIBUTES' in docs or Recurly_Adjustment#toRefundAttributes)
   * @param String indicates the refund order to apply, valid options: {'credit_first','transaction_first'}, defaults to 'credit_first'
   * @return Recurly_Invoice a new refund invoice
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

  protected function createRefundInvoice($xml_string) {
    return Recurly_Invoice::_post($this->uri() . '/refund', $xml_string, $this->_client);
  }

  protected function getNodeName() {
    return 'invoice';
  }
  protected function getWriteableAttributes() {
    return array(
      'terms_and_conditions', 'customer_notes', 'vat_reverse_charge_notes',
      'collection_method', 'net_terms', 'po_number', 'currency', 'credit_customer_notes'
    );
  }
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
