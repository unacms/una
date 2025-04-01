<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Rule extends Model
{

  protected $allowed = [
    'id',
    'namespace',
    'ruleName',
    'ruleOrder',
    'status',
    'conditions',
    'outcome',
    'deleted',
    'createdAt',
    'modifiedAt',
  ];



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("rules",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>