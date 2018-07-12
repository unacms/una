<?php

/**
 * An name/value pair.
 */
class Recurly_CustomField extends Recurly_Resource
{
  function __construct($name = null, $value = null, $client = null) {
    parent::__construct(null, $client);
    if (!is_null($name)) $this->name = $name;
    $this->value = $value;
  }

  public function has_changed() {
    return in_array('value', $this->_unsavedKeys);
  }

  // Need to make this public since Recurly_CustomFieldList doesn't inherit from
  // Recurly_Resource.
  public function populateXmlDoc(&$doc, &$node, &$obj, $nested = false) {
    if ($this->has_changed()) {
      $childNode = $node->appendChild($doc->createElement($this->getNodeName()));
      parent::populateXmlDoc($doc, $childNode, $obj, $nested);
    }
  }

  protected function getNodeName() {
    return 'custom_field';
  }

  protected function getRequiredAttributes() {
    return array('name');
  }

  protected function getWriteableAttributes() {
    return array('name', 'value');
  }
}

