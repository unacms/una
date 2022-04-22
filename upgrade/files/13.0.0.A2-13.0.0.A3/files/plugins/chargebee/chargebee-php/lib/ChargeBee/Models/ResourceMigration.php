<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class ResourceMigration extends Model
{

  protected $allowed = [
    'fromSite',
    'entityType',
    'entityId',
    'status',
    'errors',
    'createdAt',
    'updatedAt',
  ];



  # OPERATIONS
  #-----------

  public static function retrieveLatest($params, $env = null, $headers = array())
  {
    return Request::send(Request::GET, Util::encodeURIPath("resource_migrations","retrieve_latest"), $params, $env, $headers);
  }

 }

?>