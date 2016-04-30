<?php

class Recurly_Address extends Recurly_Resource {

  protected static $_writeableAttributes;

  public static function init()
  {
    Recurly_Address::$_writeableAttributes = array(
      'address1','address2','city','state',
      'zip','country','phone'
    );

  }

  protected function getNodeName() {
    return 'address';
  }
  protected function getWriteableAttributes() {
    return Recurly_Address::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }

}

Recurly_Address::init();
