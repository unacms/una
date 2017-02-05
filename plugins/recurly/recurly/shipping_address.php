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
    if ($this->isEmbedded($node)) {
      $shippingAddressNode = $node->appendChild($doc->createElement($this->getNodeName()));
      parent::populateXmlDoc($doc, $shippingAddressNode, $obj);
    } else {
      parent::populateXmlDoc($doc, $node, $obj);
    }
  }

  private function isEmbedded($node) {
    $path = explode('/', $node->getNodePath());
    $last = $path[count($path)-1];
    return $last == 'shipping_addresses';
  }
}
