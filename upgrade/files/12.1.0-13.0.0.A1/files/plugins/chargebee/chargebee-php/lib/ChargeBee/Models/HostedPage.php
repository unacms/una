<?php

class ChargeBee_HostedPage extends ChargeBee_Model
{

  protected $allowed = array('id', 'type', 'url', 'state', 'failureReason', 'passThruContent', 'embed', 'createdAt',
'expiresAt', 'updatedAt', 'resourceVersion', 'checkoutInfo');

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

  public static function checkoutOneTime($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_one_time"), $params, $env, $headers);
  }

  public static function checkoutOneTimeForItems($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_one_time_for_items"), $params, $env, $headers);
  }

  public static function checkoutNewForItems($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_new_for_items"), $params, $env, $headers);
  }

  public static function checkoutExisting($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_existing"), $params, $env, $headers);
  }

  public static function checkoutExistingForItems($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_existing_for_items"), $params, $env, $headers);
  }

  public static function updateCard($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","update_card"), $params, $env, $headers);
  }

  public static function updatePaymentMethod($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","update_payment_method"), $params, $env, $headers);
  }

  public static function managePaymentSources($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","manage_payment_sources"), $params, $env, $headers);
  }

  public static function collectNow($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","collect_now"), $params, $env, $headers);
  }

  public static function acceptQuote($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","accept_quote"), $params, $env, $headers);
  }

  public static function extendSubscription($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","extend_subscription"), $params, $env, $headers);
  }

  public static function checkoutGift($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_gift"), $params, $env, $headers);
  }

  public static function checkoutGiftForItems($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","checkout_gift_for_items"), $params, $env, $headers);
  }

  public static function claimGift($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","claim_gift"), $params, $env, $headers);
  }

  public static function retrieveAgreementPdf($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages","retrieve_agreement_pdf"), $params, $env, $headers);
  }

  public static function acknowledge($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("hosted_pages",$id,"acknowledge"), array(), $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("hosted_pages",$id), array(), $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("hosted_pages"), $params, $env, $headers);
  }

 }

?>