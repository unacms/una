<?php

class Recurly_Address extends Recurly_Resource {

  protected function getNodeName() {
    return 'address';
  }
  protected function getWriteableAttributes() {
    return array(
      'address1', 'address2', 'city', 'state',
      'zip', 'country', 'phone'
    );
  }
}
