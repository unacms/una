<?php

class ChargeBee_Item extends ChargeBee_Model
{

  protected $allowed = array('id', 'name', 'description', 'status', 'resourceVersion', 'updatedAt', 'itemFamilyId',
'type', 'isShippable', 'isGiftable', 'redirectUrl', 'enabledForCheckout', 'enabledInPortal','includedInMrr', 'itemApplicability', 'giftClaimRedirectUrl', 'unit', 'metered', 'usageCalculation','archivedAt', 'applicableItems', 'metadata');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("items"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("items",$id), array(), $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("items",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("items"), $params, $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("items",$id,"delete"), array(), $env, $headers);
  }

 }

?>