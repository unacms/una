<?php

class ChargeBee_Export extends ChargeBee_Model
{

  protected $allowed = array('operationType', 'mimeType', 'status', 'createdAt', 'id', 'download'
);

public function waitForExportCompletion($env = null, $headers = array()) {
  $count = 0;
  while($this->status == "in_process") {
     if( $count++ > 50) {
        throw new RuntimeException("Export is taking too long");
     }
     sleep(ChargeBee_Environment::$exportWaitInSecs);
     $this->_values = ChargeBee_Export::retrieve($this->id, $env, $headers)->export()->getValues();
     $this->_load();
  }
  return $this;
}



  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("exports",$id), array(), $env, $headers);
  }

  public static function revenueRecognition($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("exports","revenue_recognition"), $params, $env, $headers);
  }

  public static function deferredRevenue($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("exports","deferred_revenue"), $params, $env, $headers);
  }

 }

?>