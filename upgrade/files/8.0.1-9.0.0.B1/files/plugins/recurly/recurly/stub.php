<?php

/**
 * Resource stub for Recurly API. Call get() to retrieve the stubbed resource.
 */
class Recurly_Stub extends Recurly_Base
{
  /**
   * Stubbed object type. Useful for printing the current object as a string.
   */
  var $objectType;

  function __construct($objectType, $href, $client = null)
  {
    parent::__construct($href, $client);
    $this->objectType = $objectType;
  }

  /**
   * Retrieve the stubbed resource.
   */
  function get() {
    $stub = self::_get($this->_href, $this->_client);
    if ($this->_href && !$stub->getHref()) {
      $stub->setHref($this->_href);
    }
    return $stub;
  }

  public function __toString()
  {
    return "<Recurly_Stub[{$this->objectType}] href={$this->_href}>";
  }
}
