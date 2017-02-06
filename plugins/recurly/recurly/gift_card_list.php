<?php

class Recurly_GiftCardList extends Recurly_Pager
{
  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_GIFT_CARDS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'gift_cards';
  }
}
