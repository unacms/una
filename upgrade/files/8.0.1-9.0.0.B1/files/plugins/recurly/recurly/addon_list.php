<?php

class Recurly_AddonList extends Recurly_Pager
{
  public static function get($planCode, $client = null)
  {
    $uri = Recurly_Client::PATH_PLANS . '/' . rawurlencode($planCode) . Recurly_Client::PATH_ADDONS;
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'add_ons';
  }
}
