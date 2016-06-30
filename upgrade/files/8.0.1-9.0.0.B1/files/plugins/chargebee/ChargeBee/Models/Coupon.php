<?php

class ChargeBee_Coupon extends ChargeBee_Model
{

  protected $allowed = array('id', 'name', 'invoiceName', 'discountType', 'discountPercentage', 'discountAmount',
'discountQuantity', 'durationType', 'durationMonth', 'validTill', 'maxRedemptions', 'status','applyDiscountOn', 'applyOn', 'planConstraint', 'addonConstraint', 'createdAt', 'archivedAt','planIds', 'addonIds', 'redemptions', 'invoiceNotes', 'metaData');



  # OPERATIONS
  #-----------

  public static function create($params, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::POST, ChargeBee_Util::encodeURIPath("coupons"), $params, $env, $headers);
  }

  public static function all($params = array(), $env = null, $headers = array())
  {
    return ChargeBee_Request::sendListRequest(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("coupons"), $params, $env, $headers);
  }

  public static function retrieve($id, $env = null, $headers = array())
  {
    return ChargeBee_Request::send(ChargeBee_Request::GET, ChargeBee_Util::encodeURIPath("coupons",$id), array(), $env, $headers);
  }

 }

?>