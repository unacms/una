<?php

class ChargeBee_Event extends ChargeBee_Model
{

  protected $allowed = array('id', 'occurredAt', 'source', 'user', 'webhookStatus', 'webhookFailureReason',
'webhooks', 'eventType', 'apiVersion');

    public function content()
    {
        return new ChargeBee_Content($this->_values['content']);
    }

    public static function deserialize($json)
    {
        $webhookData = json_decode($json, true);
        if(!$webhookData) {
            throw new Exception("Response not in JSON format. Might not be a ChargeBee webhook call.");
        }
        if($webhookData != null)
        {
            if( isset($webhookData['api_version']) ) {
                $apiVersion = strtoupper($webhookData['api_version']);
                if($apiVersion != null && strcasecmp($apiVersion, ChargeBee_Environment::API_VERSION) != 0){
                    throw new RuntimeException("API version [".$apiVersion."] in response does not match "
                        ."with client library API version [".strtoupper(ChargeBee_Environment::API_VERSION)."]");
                }
            }
            return new ChargeBee_Event($webhookData);
        }
        return null;
    }


  # OPERATIONS
  #-----------

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("events"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("events",$id), array(), $env, $headers);
  }

 }

?>