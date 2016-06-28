<?php

class Recurly_PlanList extends Recurly_Pager
{
  public static function get($params = null, $client = null) {
    $uri = self::_uriWithParams(Recurly_Client::PATH_PLANS, $params);
    return new self($uri, $client);
  }

  protected function getNodeName() {
    return 'plans';
  }
}
