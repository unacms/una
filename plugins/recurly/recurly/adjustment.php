<?php

class Recurly_Adjustment extends Recurly_Resource
{
  protected static $_writeableAttributes;

  public static function init()
  {
    Recurly_Adjustment::$_writeableAttributes = array(
      'currency','unit_amount_in_cents','quantity','description',
      'accounting_code','tax_exempt','tax_code','start_date','end_date'
    );
  }

  public static function get($adjustment_uuid, $client = null) {
    return Recurly_Base::_get(Recurly_Client::PATH_ADJUSTMENTS . '/' . rawurlencode($adjustment_uuid), $client);
  }

  public function create() {
    $this->_save(Recurly_Client::POST, $this->createUriForAccount());
  }
  public function delete() {
    return Recurly_Base::_delete($this->getHref(), $this->_client);
  }

  /**
   * Allows you to refund this particular item if it's a part of
   * an invoice. It does this by calling the invoice's refund()
   * Only 'invoiced' adjustments can be refunded.
   *
   * @param Integer the quantity you wish to refund, defaults to refunding the entire quantity
   * @param Boolean indicates whether you want this adjustment refund prorated
   * @param String indicates the refund order to apply, valid options: {'credit','transaction'}, defaults to 'credit'
   * @return Recurly_Invoice the new refund invoice
   * @throws Recurly_Error if the adjustment cannot be refunded.
   */
  public function refund($quantity = null, $prorate = false, $refund_apply_order = 'credit') {
    if ($this->state == 'pending') {
      throw new Recurly_Error("Only invoiced adjustments can be refunded");
    }
    $invoice = $this->invoice->get();
    return $invoice->refund($this->toRefundAttributes($quantity, $prorate), $refund_apply_order);
  }

  /**
   * Converts this adjustment into the attributes needed for a refund.
   *
   * @param Integer the quantity you wish to refund, defaults to refunding the entire quantity
   * @param Boolean indicates whether you want this adjustment refund prorated
   * @return Array an array of refund attributes to be fed into invoice->refund()
   */
  public function toRefundAttributes($quantity = null, $prorate = false) {
    if (is_null($quantity)) $quantity = $this->quantity;

    return array('uuid' => $this->uuid, 'quantity' => $quantity, 'prorate' => $prorate);
  }

  protected function createUriForAccount() {
    if (empty($this->account_code))
      throw new Recurly_Error("'account_code' is not specified");

    return (Recurly_Client::PATH_ACCOUNTS . '/' . rawurlencode($this->account_code) .
            Recurly_Client::PATH_ADJUSTMENTS);
  }

  protected function getNodeName() {
    return 'adjustment';
  }
  protected function getWriteableAttributes() {
    return Recurly_Adjustment::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }
}

Recurly_Adjustment::init();
