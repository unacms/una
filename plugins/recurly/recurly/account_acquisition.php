<?php

/**
 * Class Recurly_AccountAcquisition
 * @property Recurly_Stub $account The associated Recurly_Account for this account acquisition.
 * @property int $cost_in_cents Total cost of marketing activities to acquire the customer.
 * @property string $currency_code Currency, 3-letter ISO code.
 * @property string $channel The method by which the customer was acquired. Allowed values: [referral, social_media, email, paid_search, organic_search, direct_traffic, marketing_content, blog, events, outbound_sales, advertising, public_relations, other].
 * @property string $subchannel A free-form field to provide additional detail on the acquisition channel.
 * @property string $campaign Identifier for the marketing campaign used to convert this account.
 * @property DateTime $created_at The date and time the account acquisition was created.
 * @property DateTime $updated_at The date and time the account acquisition was last updated.
 */
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
