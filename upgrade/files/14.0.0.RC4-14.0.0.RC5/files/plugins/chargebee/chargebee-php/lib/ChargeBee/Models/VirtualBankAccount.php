<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class VirtualBankAccount extends Model
{

  protected $allowed = [
    'id',
    'customerId',
    'email',
    'scheme',
    'bankName',
    'accountNumber',
    'routingNumber',
    'swiftCode',
    'gateway',
    'gatewayAccountId',
    'resourceVersion',
    'updatedAt',
    'createdAt',
    'referenceId',
    'deleted',
  ];



  # OPERATIONS
  #-----------

  public static function createUsingPermanentToken($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("virtual_bank_accounts","create_using_permanent_token"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("virtual_bank_accounts"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("virtual_bank_accounts",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("virtual_bank_accounts"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("virtual_bank_accounts",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function deleteLocal($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("virtual_bank_accounts",$id,"delete_local"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>