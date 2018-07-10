<?php
/**
 * class Recurly_InvoiceCollection
 * @property Recurly_Invoice $charge_invoice The invoice with any charges from the collection.
 * @property Recurly_Invoice[] $credit_invoices The array of any credit invoices created from the collection.
 */
class Recurly_InvoiceCollection extends Recurly_Resource
{
  protected function getNodeName() {
    return 'invoice_collection';
  }

  protected function getWriteableAttributes() {
   return array();
  }
}
