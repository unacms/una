<?php

class Recurly_GiftCard extends Recurly_Resource
{
  /**
   * Get a gift card by the id
   */
  public static function get($giftCardId, $client = null) {
    return Recurly_Base::_get(Recurly_GiftCard::uriForGiftCard($giftCardId), $client);
  }

  /**
   * Redeem a gift card given an account code
   */
  public function redeem($accountCode) {
    $doc = $this->createDocument();
    $root = $doc->appendChild($doc->createElement('recipient_account'));
    $root->appendChild($doc->createElement('account_code', $accountCode));
    $uri = Recurly_GiftCard::uriForGiftCard($this->redemption_code) . '/redeem';

    $this->_save(Recurly_Client::POST, $uri, $this->renderXML($doc));
  }

  public function create() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_GIFT_CARDS);
  }

  /**
   * Preview the creation and check for errors.
   *
   * Note: once preview() has been called you will not be able to call create()
   * without reassiging all the attributes.
   */
  public function preview() {
    $this->_save(Recurly_Client::POST, Recurly_Client::PATH_GIFT_CARDS . '/preview');
  }

  protected function uri() {
    if (!empty($this->_href))
      return $this->getHref();
    else
      return Recurly_GiftCard::uriForGiftCard($this->id);
  }

  protected static function uriForGiftCard($giftCardIdentifier) {
    return Recurly_Client::PATH_GIFT_CARDS. '/' . rawurlencode($giftCardIdentifier);
  }

  protected function getNodeName() {
    return 'gift_card';
  }
  protected function getWriteableAttributes() {
    if ($this->redemption_code) {
      return array('redemption_code');
    } else {
      return array(
        'product_code','unit_amount_in_cents','delivery',
        'gifter_account','currency','delivery'
      );
    }
  }
}
