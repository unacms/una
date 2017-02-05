<?php

class Recurly_MeasuredUnit extends Recurly_Resource
{
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
    return array(
      'name', 'display_name', 'description'
    );
  }
}
