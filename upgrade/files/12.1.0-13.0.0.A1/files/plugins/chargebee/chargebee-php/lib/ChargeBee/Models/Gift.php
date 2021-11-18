<?php

class ChargeBee_Gift extends ChargeBee_Model
{

  protected $allowed = array('id', 'status', 'scheduledAt', 'autoClaim', 'noExpiry', 'claimExpiryDate', 'resourceVersion',
'updatedAt', 'gifter', 'giftReceiver', 'giftTimelines');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("gifts"), $params, $env, $headers);
  }

  public static function createForItems($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("gifts","create_for_items"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("gifts",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("gifts"), $params, $env, $headers);
  }

  public static function claim($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("gifts",$id,"claim"), array(), $env, $headers);
  }

  public static function cancel($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("gifts",$id,"cancel"), array(), $env, $headers);
  }

  public static function updateGift($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("gifts",$id,"update_gift"), $params, $env, $headers);
  }

 }

?>