<?php

class ChargeBee_Usage extends ChargeBee_Model
{

  protected $allowed = array('id', 'usageDate', 'subscriptionId', 'itemPriceId', 'invoiceId', 'lineItemId',
'quantity', 'source', 'note', 'resourceVersion', 'updatedAt', 'createdAt');



  # OPERATIONS
  #-----------

  public static function create($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("subscriptions",$id,"usages"), $params, $env, $headers);
  }

  public static function retrieve($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("subscriptions",$id,"usages"), $params, $env, $headers);
  }

  public static function delete($id, $params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("subscriptions",$id,"delete_usage"), $params, $env, $headers);
  }

  public static function all($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("usages"), $params, $env, $headers);
  }

  public static function pdf($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("usages","pdf"), $params, $env, $headers);
  }

 }

?>