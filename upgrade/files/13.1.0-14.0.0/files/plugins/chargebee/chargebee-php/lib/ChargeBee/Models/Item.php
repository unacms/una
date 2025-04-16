<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class Item extends Model
{

  protected $allowed = [
    'id',
    'name',
    'externalName',
    'description',
    'status',
    'resourceVersion',
    'updatedAt',
    'itemFamilyId',
    'type',
    'isShippable',
    'isGiftable',
    'redirectUrl',
    'enabledForCheckout',
    'enabledInPortal',
    'includedInMrr',
    'itemApplicability',
    'giftClaimRedirectUrl',
    'unit',
    'metered',
    'usageCalculation',
    'archivedAt',
    'channel',
    'applicableItems',
    'bundleItems',
    'bundleConfiguration',
    'metadata',
    'deleted',
    'businessEntityId',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metadata" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::GET, Util::encodeURIPath("items",$id), array(), $env, $headers, null, false, $jsonKeys);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
        "metadata" => 0,
    );
    return Request::send(Request::POST, Util::encodeURIPath("items",$id), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("items"), $params, $env, $headers, null, false, $jsonKeys);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    $jsonKeys = array(
    );
    return Request::send(Request::POST, Util::encodeURIPath("items",$id,"delete"), array(), $env, $headers, null, false, $jsonKeys);
  }

 }

?>