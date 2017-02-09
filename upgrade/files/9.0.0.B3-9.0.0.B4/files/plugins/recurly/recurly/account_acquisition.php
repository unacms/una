<?php

class Recurly_AccountAcquisition extends Recurly_Resource
{
  public static function get($accountCode, $client = null) {
    return Recurly_Base::_get(Recurly_AccountAcquisition::uriForAccountAcquisition($accountCode), $client);
  }

  public static function deleteForAccount($accountCode, $client = null) {
    return Recurly_Base::_delete(Recurly_AccountAcquisition::uriForAccountAcquisition($accountCode), $client);
  }

  protected static function uriForAccountAcquisition($accountCode) {
    return '/accounts/' . rawurlencode($accountCode) . '/acquisition';
  }

  public function create() {
    $this->update();
  }

  public function update() {
    $this->_save(Recurly_Client::PUT, $this->uri());
  }

  public function delete() {
    return Recurly_Base::_delete($this->uri(), $this->_client);
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else if (!empty($this->account_code))
      return Recurly_AccountAcquisition::uriForAccountAcquisition($this->account_code);
    else
      throw new Recurly_Error("'account_code' not specified.");
  }

  protected function getNodeName() {
    return 'account_acquisition';
  }
  protected function getWriteableAttributes() {
    return array(
      'cost_in_cents', 'currency', 'channel', 'subchannel', 'campaign',
    );
  }
}
