<?php

class ChargeBee_SiteMigrationDetail extends ChargeBee_Model
{

  protected $allowed = array('entityId', 'otherSiteName', 'entityIdAtOtherSite', 'migratedAt', 'entityType',
'status');



  # OPERATIONS
  #-----------

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("site_migration_details"), $params, $env, $headers);
  }

 }

?>