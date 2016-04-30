<?php

class Recurly_Note extends Recurly_Resource
{
  protected function getNodeName() {
    return 'note';
  }

  protected function getWriteableAttributes() {
   return array();
  }
  
  protected function getRequiredAttributes() {
    return array();
  }
}
