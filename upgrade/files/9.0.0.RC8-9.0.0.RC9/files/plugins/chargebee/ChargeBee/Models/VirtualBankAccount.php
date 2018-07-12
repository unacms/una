<?php

class ChargeBee_VirtualBankAccount extends ChargeBee_Model
{

  protected $allowed = array('id', 'customerId', 'email', 'bankName', 'accountNumber', 'routingNumber', 'swiftCode',
'gateway', 'gatewayAccountId', 'referenceId', 'deleted');



  # OPERATIONS
  #-----------

  public static function createUsingPermanentToken($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("virtual_bank_accounts","create_using_permanent_token"), $params, $env, $headers);
  }

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("virtual_bank_accounts"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("virtual_bank_accounts",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("virtual_bank_accounts"), $params, $env, $headers);
  }

 }

?>