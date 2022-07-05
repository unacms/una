<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Feature extends Model
{

  protected $allowed = [
    'id',
    'name',
    'description',
    'status',
    'type',
    'unit',
    'resourceVersion',
    'updatedAt',
    'createdAt',
    'levels',
  ];



  # OPERATIONS
  #-----------

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("features"), $params, $env, $headers);
  }

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("features",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id,"delete"), array(), $env, $headers);
  }

  public static function activate($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id,"activate_command"), array(), $env, $headers);
  }

  public static function archive($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id,"archive_command"), array(), $env, $headers);
  }

  public static function reactivate($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("features",$id,"reactivate_command"), array(), $env, $headers);
  }

 }

?>