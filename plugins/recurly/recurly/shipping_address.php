<?php

class Recurly_ShippingAddress extends Recurly_Resource
{
  public function update() {
    $this->_save(Recurly_Client::PUT, $this->getHref());
  }

  protected function getNodeName() {
    return 'shipping_address';
  }
  protected function getWriteableAttributes() {
    return array(
      'address1', 'address2', 'city', 'state',
      'zip', 'country', 'phone', 'email', 'nickname',
      'first_name', 'last_name', 'company'
    );
  }
  protected function populateXmlDoc(&$doc, &$node, &$obj, $nested = false) {
    if ($this->isEmbedded($node, 'shipping_addresses')) {
      $shippingAddressNode = $node->appendChild($doc->createElement($this->getNodeName()));
      parent::populateXmlDoc($doc, $shippingAddressNode, $obj, $nested);
    } else {
      parent::populateXmlDoc($doc, $node, $obj, $nested);
    }
  }
}
