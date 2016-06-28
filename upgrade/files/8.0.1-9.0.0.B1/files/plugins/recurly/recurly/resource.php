<?php

abstract class Recurly_Resource extends Recurly_Base
{
  protected $_values;
  protected $_unsavedKeys;
  protected $_errors;

  abstract protected function getNodeName();
  abstract protected function getWriteableAttributes();
  abstract protected function getRequiredAttributes();

  public function __construct($href = null, $client = null)
  {
    parent::__construct($href, $client);
    $this->_values = array();
    $this->_unsavedKeys = array();
    $this->_errors = new Recurly_ErrorList();
  }


  public function __set($k, $v)
  {
    $this->_values[$k] = $v;
    $this->_unsavedKeys[$k] = true;
  }
  public function __isset($k)
  {
    return isset($this->_values[$k]);
  }
  public function __unset($k)
  {
    unset($this->_values[$k]);
  }
  public function &__get($key)
  {
    if (isset($this->_values[$key])) {
      return $this->_values[$key];
    //} else if ($this->_attributes->include($key)) {
    //  return null;
    } else if ($key == 'errors') {
      return $this->_errors;
    } else {
      $null_val = null;
      return $null_val;
    }
  }

  /**
   * Return all of the values associated with this resource.
   *
   * @return array
   *   The array of values stored with this resource.
   */
  public function getValues() {
    return $this->_values;
  }

  public function getErrors() {
    return $this->_errors;
  }

  /**
   * Does a mass assignment on this resource's values
   *
   * @param array
   *   The array of values to set on the resource.
   */
  public function setValues($values) {
    foreach($values as $key => $value) {
      $this->$key = $value;
    }
    return $this;
  }

  protected function _save($method, $uri, $data = null)
  {
    $this->_errors = array(); // reset errors

    if (is_null($data)) {
      $data = $this->xml();
    }

    $response = $this->_client->request($method, $uri, $data);
    $response->assertValidResponse();

    if (isset($response->body)) {
      Recurly_Resource::__parseXmlToUpdateObject($response->body);
    }
    $response->assertSuccessResponse($this);
  }


  public function xml()
  {
    $doc = $this->createDocument();
    $root = $doc->appendChild($doc->createElement($this->getNodeName()));
    $this->populateXmlDoc($doc, $root, $this);
    return $this->renderXML($doc);
  }

  public function createDocument() {
    return new DOMDocument("1.0");
  }

  public function renderXML($doc) {
    // To be able to consistently run tests across different XML libraries,
    // favor `<foo></foo>` over `<foo/>`.
    return $doc->saveXML(null, LIBXML_NOEMPTYTAG);
  }

  protected function populateXmlDoc(&$doc, &$node, &$obj, $nested = false)
  {
    $attributes = $obj->getChangedAttributes($nested);

    foreach ($attributes as $key => $val) {
      if ($val instanceof Recurly_CurrencyList) {
        $val->populateXmlDoc($doc, $node);
      } else if ($val instanceof Recurly_Resource) {
        $attribute_node = $node->appendChild($doc->createElement($key));
        $this->populateXmlDoc($doc, $attribute_node, $val, true);
      } else if (is_array($val)) {
        $attribute_node = $node->appendChild($doc->createElement($key));
        foreach ($val as $child => $childValue) {
          if (is_null($child) || is_null($childValue)) {
            continue;
          }
          elseif (is_string($child)) {
            // e.g. "<discount_in_cents><USD>1000</USD></discount_in_cents>"
            $attribute_node->appendChild($doc->createElement($child, $childValue));
          }
          elseif (is_int($child)) {
            if (is_object($childValue)) {
              // e.g. "<subscription_add_ons><subscription_add_on>...</subscription_add_on></subscription_add_ons>"
              $childValue->populateXmlDoc($doc, $attribute_node, $childValue);
            }
            elseif (substr($key, -1) == "s") {
              // e.g. "<plan_codes><plan_code>gold</plan_code><plan_code>monthly</plan_code></plan_codes>"
              $attribute_node->appendChild($doc->createElement(substr($key, 0, -1), $childValue));
            }
          }
        }
      } else if (is_null($val)) {
        $domAttribute = $doc->createAttribute('nil');
        $domAttribute->value = 'nil';
        $attribute_node = $node->appendChild($doc->createElement($key));
        $attribute_node->appendChild($domAttribute);
      } else {
        if ($val instanceof DateTime) {
          $val = $val->format('c');
        } else if (is_bool($val)) {
          $val = ($val ? 'true' : 'false');
        }
        $attribute_node = $node->appendChild($doc->createElement($key));
        $attribute_node->appendChild($doc->createTextNode($val));
      }
    }
  }

  protected function getChangedAttributes($nested = false)
  {
    $attributes = array();
    $writableAttributes = $this->getWriteableAttributes();
    $requiredAttributes = $this->getRequiredAttributes();

    foreach($writableAttributes as $attr) {
      if(!array_key_exists($attr, $this->_values)) { continue; }

      if(isset($this->_unsavedKeys[$attr]) ||
         $nested && in_array($attr, $requiredAttributes) ||
         (is_array($this->_values[$attr]) || $this->_values[$attr] instanceof ArrayAccess))
      {
        $attributes[$attr] = $this->$attr;
      }

      // Check for nested objects.
      if ($this->_values[$attr] instanceof Recurly_Resource) {
        if ($this->_values[$attr]->getChangedAttributes()) {
          $attributes[$attr] = $this->$attr;
        }
      }
    }

    return $attributes;
  }

  protected function updateErrorAttributes()
  {
    if (sizeof($this->_errors) > 0) {
      for ($i = sizeof($this->_errors) - 1; $i >= 0; $i--) {
        $error = $this->_errors[$i];

        if (isset($error->field)) {
          if (substr($error->field, 0, strlen($this->getNodeName()) + 1) == ($this->getNodeName() . '.'))
            $error->field = substr($error->field, strlen($this->getNodeName()) + 1);
        }

        // TODO: If there are more dots, then apply these to sub elements
      }
    }
  }
}

