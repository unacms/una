<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class PriceVariant extends Model
{

  protected $allowed = [
    'id',
    'name',
    'externalName',
    'variantGroup',
    'description',
    'status',
    'createdAt',
    'resourceVersion',
    'updatedAt',
    'archivedAt',
    'attributes',
    'businessEntityId',
    'deleted',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("price_variants"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("price_variants",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("price_variants",$id), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("price_variants",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("price_variants"), $params, $env, $headers, null, false, $jsonKeys);
  }

 }

?>