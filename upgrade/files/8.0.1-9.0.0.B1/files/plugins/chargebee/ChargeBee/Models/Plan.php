<?php

class ChargeBee_Plan extends ChargeBee_Model
{

  protected $allowed = array('id', 'name', 'invoiceName', 'description', 'price', 'period', 'periodUnit', 'trialPeriod',
'trialPeriodUnit', 'chargeModel', 'freeQuantity', 'setupCost', 'downgradePenalty', 'status','archivedAt', 'billingCycles', 'redirectUrl', 'enabledInHostedPages', 'enabledInPortal', 'taxCode','invoiceNotes', 'taxable', 'metaData');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans"), $params, $env, $headers);
  }

  public static function update($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans",$id), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("plans"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("plans",$id), array(), $env, $headers);
  }

  public static function delete($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("plans",$id,"delete"), array(), $env, $headers);
  }

 }

?>