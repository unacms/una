<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Installment extends Model
{

  protected $allowed = [
    'id',
    'invoiceId',
    'date',
    'amount',
    'status',
    'createdAt',
    'resourceVersion',
    'updatedAt',
  ];



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("installments",$id), array(), $env, $headers);
  }

  public static function all($params, $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("installments"), $params, $env, $headers);
  }

 }

?>