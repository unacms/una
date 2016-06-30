<?php

class ChargeBee_Estimate extends ChargeBee_Model
{

  protected $allowed = array('createdAt', 'subscriptionEstimate', 'invoiceEstimate', 'nextInvoiceEstimate',
'creditNoteEstimates');



  # OPERATIONS
  #-----------

  public static function createSubscription($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("estimates","create_subscription"), $params, $env, $headers);
  }

  public static function updateSubscription($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("estimates","update_subscription"), $params, $env, $headers);
  }

  public static function renewalEstimate($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("subscriptions",$id,"renewal_estimate"), $params, $env, $headers);
  }

 }

?>