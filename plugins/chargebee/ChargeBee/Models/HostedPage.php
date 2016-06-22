<?php

class ChargeBee_HostedPage extends ChargeBee_Model
{

  protected $allowed = array('id', 'type', 'url', 'state', 'failureReason', 'passThruContent', 'embed', 'createdAt',
'expiresAt');

  public function content()
  {
    if(isset($this->_values['content']))
    {
        return new ChargeBee_Content($this->_values['content']);
    }
    return null;
  }

  # OPERATIONS
  #-----------

  public static function checkoutNew($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_new"), $params, $env, $headers);
  }

  public static function checkoutExisting($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_existing"), $params, $env, $headers);
  }

  public static function updateCard($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","update_card"), $params, $env, $headers);
  }

  public static function updatePaymentMethod($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","update_payment_method"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("hosted_pages",$id), array(), $env, $headers);
  }

 }

?>