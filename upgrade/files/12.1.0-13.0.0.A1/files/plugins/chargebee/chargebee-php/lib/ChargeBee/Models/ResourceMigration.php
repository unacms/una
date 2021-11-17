<?php

class ChargeBee_ResourceMigration extends ChargeBee_Model
{

  protected $allowed = array('fromSite', 'entityType', 'entityId', 'status', 'errors', 'createdAt', 'updatedAt'
);



  # OPERATIONS
  #-----------

  public static function retrieveLatest($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("resource_migrations","retrieve_latest"), $params, $env, $headers);
  }

 }

?>