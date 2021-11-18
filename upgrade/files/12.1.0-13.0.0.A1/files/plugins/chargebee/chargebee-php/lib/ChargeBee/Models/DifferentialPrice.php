<?php

class ChargeBee_DifferentialPrice extends ChargeBee_Model
{

  protected $allowed = array('id', 'itemPriceId', 'parentItemId', 'price', 'priceInDecimal', 'status', 'resourceVersion',
'updatedAt', 'createdAt', 'modifiedAt', 'tiers', 'currencyCode', 'parentPeriods');



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("item_prices",$id,"differential_prices"), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("differential_prices",$id), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("differential_prices",$id), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("differential_prices",$id,"delete"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("differential_prices"), $params, $env, $headers);
  }

 }

?>