<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PortalSession extends Model
{

  protected $allowed = [
    'id',
    'token',
    'accessUrl',
    'redirectUrl',
    'status',
    'createdAt',
    'expiresAt',
    'customerId',
    'loginAt',
    'logoutAt',
    'loginIpaddress',
    'logoutIpaddress',
    'linkedCustomers',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("portal_sessions"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("portal_sessions",$id), array(), $env, $headers);
  }

  public static function logout($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("portal_sessions",$id,"logout"), array(), $env, $headers);
  }

  public static function activate($id, $params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("portal_sessions",$id,"activate"), $params, $env, $headers);
  }

 }

?>