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
  function get($params = null) {
    $uri = self::_uriWithParams($this->_href, $params);
    $object = self::_get($uri, $this->_client);
    if ($this->_href && !$object->getHref()) {
      $object->setHref($this->_href);
    }
    return $object;
  }

  public function __toString()
  {
    return "<Recurly_Stub[{$this->objectType}] href={$this->_href}>";
  }
}
