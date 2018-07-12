<?php

/**
 * Class Recurly_SubscriptionAddOn
 * @property string $add_on_code The code for the Add-On.
 * @property int $unit_amount_in_cents Price of 1 unit of the add-on in cents. Max 10000000.
 * @property int $quantity Optionally override the default quantity of 1.
 * @property float $usage_percentage If add_on_type = usage and usage_type = percentage, you can set a custom usage_percentage for the subscription add-on. Must be between 0.0000 and 100.0000.
 * @property string $revenue_schedule_type Optional field for setting a revenue schedule type. This will determine how revenue for the associated Subscription Add-On should be recognized. When creating a Subscription Add-On, available schedule types are: [never, evenly, at_range_start, at_range_end]. If no revenue_schedule_type is set, the Subscription Add-On will inherit the revenue_schedule_type from its Plan Add-On.
 */
class Recurly_SubscriptionAddOn extends Recurly_Resource
{

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
    parent::populateXmlDoc($doc, $addonNode, $obj, $nested);
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
  public function __toString() {
    $class = get_class($this);
    $values = $this->__valuesString();
    return "<$class $values>";
  }
}
