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
    'metadata',
  ];



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("items"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("items",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("items",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("items"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return Request::send(Request::POST, Util::encodeURIPath("items",$id,"delete"), array(), $env, $headers);
  }

 }

?>