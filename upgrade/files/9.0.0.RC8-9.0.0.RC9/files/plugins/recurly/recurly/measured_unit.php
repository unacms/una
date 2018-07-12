<?php

/**
 * Class Recurly_MeasuredUnit
 * @property int $id The unique identifier of the account.
 * @property string $name Unique internal name of the measured unit on your site.
 * @property string $display_name Display name for the measured unit. We recommend the singular version. (e.g. - GB, API Call, Email).
 * @property string $description Optional internal description.
 */
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
