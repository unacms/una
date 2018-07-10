<?php

/**
 * Example:
 * <code>
 * $post_xml = file_get_contents ("php://input");
 * $notification = new Recurly_PushNotification($post_xml);
 * </code>
 */
class Recurly_PushNotification
{
  /* Notification type:
   *    new_account_notification
   *    updated_account_notification
   *    canceled_account_notification
   *    new_subscription_notification
   *    updated_subscription_notification
   *    canceled_subscription_notification
   *    expired_subscription_notification
   *    successful_payment_notification
   *    failed_payment_notification
   *    successful_refund_notification
   *    void_payment_notification
   *    new_invoice_notification
   *    closed_invoice_notification
   *    past_due_invoice_notification
   */
  var $type;

  var $account;
  var $subscription;
  var $transaction;
  var $invoice;

  function __construct($post_xml)
  {
    $this->parseXml($post_xml);
  }

  function parseXml($post_xml)
  {
    if (!@simplexml_load_string ($post_xml)) {
      return;
    }
    $xml = new SimpleXMLElement ($post_xml);

    $this->type = $xml->getName();

    foreach ($xml->children() as $child_node)
    {
      switch ($child_node->getName())
      {
        case 'account':
          $this->account = $child_node;
          break;
        case 'subscription':
          $this->subscription = $child_node;
          break;
        case 'transaction':
          $this->transaction = $child_node;
          break;
        case 'invoice':
          $this->invoice = $child_node;
          break;
      }
    }
  }
}
