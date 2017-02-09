<?php

class Recurly_Delivery extends Recurly_Resource
{
  protected function getNodeName() {
    return 'delivery';
  }
  protected function getWriteableAttributes() {
    return array(
      'method','email_address','first_name','last_name',
      'address','gifter_name','personal_message'
    );
  }
}
