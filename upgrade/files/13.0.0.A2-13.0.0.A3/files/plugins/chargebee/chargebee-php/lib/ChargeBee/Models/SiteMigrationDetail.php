<?php

namespace ChargeBee\ChargeBee\Models;

use ChargeBee\ChargeBee\Model;
use ChargeBee\ChargeBee\Request;
use ChargeBee\ChargeBee\Util;

class SiteMigrationDetail extends Model
{

  protected $allowed = [
    'entityId',
    'otherSiteName',
    'entityIdAtOtherSite',
    'migratedAt',
    'entityType',
    'status',
  ];



  # OPERATIONS
  #-----------

  public static function all($params = array(), $env = null, $headers = array())
  {
    return Request::sendListRequest(Request::GET, Util::encodeURIPath("site_migration_details"), $params, $env, $headers);
  }

 }

?>