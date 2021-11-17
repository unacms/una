<?php

class ChargeBee_TimeMachine extends ChargeBee_Model
{

  protected $allowed = array('name', 'timeTravelStatus', 'genesisTime', 'destinationTime', 'failureCode', 'failureReason',
'errorJson');

public function waitForTimeTravelCompletion($env = null) {
    $count = 0;
    $tm = $this;
    while($this->timeTravelStatus == "in_progress") {
      if($count++ > 30){
          throw new RuntimeException("The time travel is taking too much time");
      }
      sleep(ChargeBee_Environment::$timeMachineWaitInSecs);
      $this->_values = ChargeBee_TimeMachine::retrieve($this->name,$env)->timeMachine()->getValues();
      $this->_load();
    }
    if($this->timeTravelStatus == "failed" ) {
      $errorJSON = json_decode($this->errorJson, true);
      $httpCode = $errorJSON['http_code'];
      throw new ChargeBee_OperationFailedException($httpCode, $errorJSON);
    }
    if($this->timeTravelStatus != "in_progress"
           && $this->timeTravelStatus != "succeeded"
           && $this->timeTravelStatus != "failed" ) {
       throw new RuntimeException("Time travel status is in wrong state " . $this->timeTravelStatus);
    }
    return $this;
 }


  # OPERATIONS
  #-----------

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("time_machines",$id), array(), $env, $headers);
  }

  public static function startAfresh($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("time_machines",$id,"start_afresh"), $params, $env, $headers);
  }

  public static function travelForward($id, $params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("time_machines",$id,"travel_forward"), $params, $env, $headers);
  }

 }

?>