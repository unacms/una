<?php

class Recurly_SubscriptionAddOn extends Recurly_Resource {

  protected function getNodeName() {
    return 'subscription_add_on';
  }

  protected function getWriteableAttributes() {
    return array(
      'add_on_code',
      'quantity',
      'unit_amount_in_cents',
      'add_on_type',
      'usage_type',
      'usage_percentage',
      'revenue_schedule_type',
    );
  }

  protected function populateXmlDoc(&$doc, &$node, &$obj, $nested = false) {
    $addonNode = $node->appendChild($doc->createElement($this->getNodeName()));
    parent::populateXmlDoc($doc, $addonNode, $obj);
  }

  protected function getChangedAttributes($nested = false) {
    // Ignore attributes that can't be updated
    $immutable = array(
      'name' => 0,
      'add_on_type' => 0,
      'usage_type' => 0,
      'usage' => 0,
      'measured_unit' => 0,
    );
    return array_diff_key($this->_values, $immutable);
  }

  /**
   * Pretty string version of the object
   */
  public function __toString()
  {
    $class = get_class($this);
    $values = $this->__valuesString();
    return "<$class $values>";
  }
}
