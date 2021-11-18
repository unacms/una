<?php

/**
 * An name/value pair.
 * @property string $name The name of the custom field
 * @property string $value The value of the custom field
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
      // If the name as changed so it's always output.
      $this->_unsavedKeys['name'] = true;

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

