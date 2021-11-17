<?php

class Recurly_CurrencyList implements ArrayAccess, Countable, IteratorAggregate
{
  private $currencies;
  private $nodeName;

  function __construct($nodeName) {
    $this->nodeName = $nodeName;
    $this->currencies = array();
  }

  public function addCurrency($currencyCode, $amountInCents) {
    if (is_string($currencyCode) && strlen($currencyCode) == 3) {
      $this->currencies[$currencyCode] = new Recurly_Currency($currencyCode, $amountInCents);
    }
  }

  public function getCurrency($currencyCode) {
    return isset($this->currencies[$currencyCode]) ? $this->currencies[$currencyCode] : null;
  }

  public function offsetSet($offset, $value) {
    return $this->addCurrency($offset, $value);
  }

  public function offsetExists($offset) {
    return isset($this->currencies[$offset]);
  }

  public function offsetUnset($offset) {
    unset($this->currencies[$offset]);
  }

  public function offsetGet($offset) {
    return $this->getCurrency($offset);
  }

  public function __set($k, $v) {
    return $this->offsetSet($k, $v);
  }

  public function __get($k) {
    return $this->offsetGet($k);
  }

  public function count() {
    return count($this->currencies);
  }

  public function getIterator() {
    return new ArrayIterator($this->currencies);
  }

  public function populateXmlDoc(&$doc, &$node) {
    // Don't emit an element if there are no currencies.
    if ($this->currencies) {
      $currencyNode = $node->appendChild($doc->createElement($this->nodeName));
      foreach($this->currencies as $currency) {
        $currencyNode->appendChild($doc->createElement($currency->currencyCode, $currency->amount_in_cents));
      }
    }
  }

  public function __toString() {
    $values = array();
    foreach($this->currencies as $currencyCode => $currency) {
      $amount = isset($currency->amount_in_cents) ? number_format($currency->amount(), 2) : 'null';
      $values[] = "{$currency->currencyCode}={$amount}";
    }
    $values = implode($values, ', ');
    return "<Recurly_CurrencyList [$values]>";
  }
}
