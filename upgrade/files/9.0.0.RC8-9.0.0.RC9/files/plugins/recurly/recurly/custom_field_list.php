<?php

// Recurly only stores string values so if you have other data types you should
// explicitly handle the casting/conversion yourself. Null or empty string
// values are used to clear a value.
class Recurly_CustomFieldList extends ArrayObject
{
  public function offsetSet($index, $value) {
    if (!$value instanceof Recurly_CustomField) {
      throw new Exception("value must be an instance of Recurly_CustomField");
    }

    if (is_null($index)) {
      $index = $value->name;
    }
    else if ($index != $value->name) {
      throw new Exception("key: '{$index}' does not match fields's name: '{$value->name}'");
    }

    parent::offsetSet($index, $value);
  }

  public function offsetUnset($index) {
    parent::offsetSet($index, new Recurly_CustomField($index, null));
  }

  public function populateXmlDoc(&$doc, &$node) {
    $customFieldsNode = $doc->createElement('custom_fields');

    foreach($this->getIterator() as $field) {
      $field->populateXmlDoc($doc, $customFieldsNode, $field);
    }
    // Don't emit anything if there are no children.
    if ($customFieldsNode->hasChildNodes()) {
      $node->appendChild($customFieldsNode);
    }
  }

  public function __toString() {
    $values = array();
    foreach($this->getIterator() as $field) {
      $values[] = "{$field->name}={$field->value}";
    }
    $values = implode($values, ', ');
    return "<Recurly_CustomFieldList [$values]>";
  }
}
