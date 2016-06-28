<?php

class Recurly_MeasuredUnit extends Recurly_Resource
{
  protected static $_writeableAttributes;

  public static function init()
  {
    Recurly_MeasuredUnit::$_writeableAttributes = array(
      'name','display_name','description'
    );
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_MEASURED_UNITS);
  }

  public static function get($id, $client = null) {
    return Recurly_Base::_get(Recurly_MeasuredUnit::uriForMeasuredUnit($id), $client);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_Addon::uriForMeasuredUnit($this->id);
  }
  protected static function uriForMeasuredUnit($id) {
    return Recurly_Client::PATH_MEASURED_UNITS . '/' . rawurlencode($id);
  }

  protected function getNodeName() {
    return 'measured_unit';
  }
  protected function getWriteableAttributes() {
    return Recurly_MeasuredUnit::$_writeableAttributes;
  }
  protected function getRequiredAttributes() {
    return array();
  }
}

Recurly_MeasuredUnit::init();
