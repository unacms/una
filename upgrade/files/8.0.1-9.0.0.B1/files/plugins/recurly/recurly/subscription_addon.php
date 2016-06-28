<?php

class Recurly_SubscriptionAddOn extends Recurly_Resource {

  protected static $_writeableAttributes;

  public static function init() {
    Recurly_SubscriptionAddOn::$_writeableAttributes = array(
      'add_on_code',
      'quantity',
      'unit_amount_in_cents',
      'add_on_type',
      'usage_type',
      'usage_percentage'
    );
  }

  protected function getNodeName() {
    return 'subscription_add_on';
  }

  protected function getWriteableAttributes() {
    return Recurly_SubscriptionAddOn::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }

  protected function populateXmlDoc(&$doc, &$node, &$obj, $nested = false) {
    $addonNode = $node->appendChild($doc->createElement($this->getNodeName()));
    parent::populateXmlDoc($doc, $addonNode, $obj);
  }

  protected function getChangedAttributes($nested = false) {
    // Ignore the name, it can't be changed.
    return array_diff_key($this->_values, array('name' => 0));
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

Recurly_SubscriptionAddOn::init();

