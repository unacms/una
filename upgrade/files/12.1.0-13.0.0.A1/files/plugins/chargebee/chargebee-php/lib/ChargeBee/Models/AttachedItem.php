<?php

class ChargeBee_AttachedItem extends ChargeBee_Model
{

  protected $allowed = array('id', 'parentItemId', 'itemId', 'type', 'status', 'quantity', 'quantityInDecimal',
'billingCycles', 'chargeOnEvent', 'chargeOnce', 'createdAt', 'resourceVersion', 'updatedAt');



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("items",$id,"attached_items"), $params, $env, $headers);
  }

  public static function update($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("attached_items",$id), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("attached_items",$id), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("attached_items",$id,"delete"), $params, $env, $headers);
  }

  public static function all($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("items",$id,"attached_items"), $params, $env, $headers);
  }

  public static function listInternal($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("attached_items","list_internal"), $params, $env, $headers);
  }

 }

?>