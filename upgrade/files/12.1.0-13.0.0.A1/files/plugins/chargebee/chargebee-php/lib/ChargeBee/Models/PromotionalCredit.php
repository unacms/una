<?php

class ChargeBee_PromotionalCredit extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'type', 'amountInDecimal', 'amount', 'currencyCode', 'description',
'creditType', 'reference', 'closingBalance', 'doneBy', 'createdAt');



  # OPERATIONS
  #-----------

  public static function add($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("promotional_credits","add"), $params, $env, $headers);
  }

  public static function deduct($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("promotional_credits","deduct"), $params, $env, $headers);
  }

  public static function set($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("promotional_credits","set"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("promotional_credits"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("promotional_credits",$id), array(), $env, $headers);
  }

 }

?>