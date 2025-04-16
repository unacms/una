<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class InstallmentConfig extends Model
{

  protected $allowed = [
    'id',
    'description',
    'numberOfInstallments',
    'periodUnit',
    'period',
    'preferredDay',
    'createdAt',
    'resourceVersion',
    'updatedAt',
    'installments',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("installment_configs"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("installment_configs",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("installment_configs",$id,"delete"), array(), $env, $headers);
  }

 }

?>