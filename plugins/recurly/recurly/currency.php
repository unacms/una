<?php

/**
 * An amount with a currency.
 */
class Recurly_Currency
{
  var $currencyCode;
  var $amount_in_cents;
  
  function __construct($currencyCode, $amountInCents) {
    $this->currencyCode = $currencyCode;
    $this->amount_in_cents = $amountInCents;
  }

  /**
   * Amount in dollars, or whatever your currency may be.
   * @return float Amount in dollars
   */
  public function amount() {
    return $this->amount_in_cents / 100.0;
  }
  
  public function __toString() {
    return "<Recurly_Currency currencyCode=\"{$this->currencyCode}\" amount_in_cents={$this->amount_in_cents}>";
  }
}
