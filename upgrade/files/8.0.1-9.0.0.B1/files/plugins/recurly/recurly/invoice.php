<?php

class Recurly_Invoice extends Recurly_Resource
{
  protected static $_writeableAttributes;

  public static function init()
  {
    Recurly_Invoice::$_writeableAttributes = array('terms_and_conditions', 'customer_notes', 'vat_reverse_charge_notes', 'collection_method', 'net_terms', 'po_number');
  }

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
   * @return Recurly_Invoice invoice on success
   */
  public static function invoicePendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES;
    $invoice = new self();
    return self::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  /**
   * Previews an invoice for an account using its pending charges
   * @param string Unique account code
   * @param array additional invoice attributes (see writeableAttributes)
   * @return Recurly_Invoice invoice on success
   */
  public static function previewPendingCharges($accountCode, $attributes = array(), $client = null) {
    $uri = Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($accountCode) . Recurly_Client::PATH_INVOICES . '/preview';
    $invoice = new self();
    return self::_post($uri, $invoice->setValues($attributes)->xml(), $client);
  }

  public function markSuccessful() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/mark_successful');
  }
  public function markFailed() {
    $this->_save(Recurly_Client::PUT, $this->uri() . '/mark_failed');
  }

  public function invoiceNumberWithPrefix() {
    return $this->invoice_number_prefix . $this->invoice_number;
  }

  /**
   * Refunds an open amount from the invoice and returns a new refund invoice
   * @param Integer amount in cents to refund from this invoice
   * @param String indicates the refund order to apply, valid options: {'credit','transaction'}, defaults to 'credit'
   * @return Recurly_Invoice a new refund invoice
   */
  public function refundAmount($amount_in_cents, $refund_apply_order = 'credit') {
    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('refund_apply_order', $refund_apply_order));
    $root->appendChild($doc->createElement('amount_in_cents', $amount_in_cents));

    return $this->createRefundInvoice($this->renderXML($doc));
  }

  /**
   * Refunds given line items from an invoice and returns new refund invoice
   * @param Array refund attributes or Array of refund attributes to refund (see 'REFUND ATTRIBUTES' in docs or Recurly_Adjustment#toRefundAttributes)
   * @param String indicates the refund order to apply, valid options: {'credit','transaction'}, defaults to 'credit'
   * @return Recurly_Invoice a new refund invoice
   */
  public function refund($line_items, $refund_apply_order = 'credit') {
    if (isset($line_items['uuid'])) { $line_items = array($line_items); }

    $doc = $this->createDocument();

    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $root->appendChild($doc->createElement('refund_apply_order', $refund_apply_order));
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
    return self::_post($this->uri() . '/refund', $xml_string, $this->_client);
  }

  protected function getNodeName() {
    return 'invoice';
  }
  protected function getWriteableAttributes() {
    return Recurly_Invoice::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
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

Recurly_Invoice::init();
