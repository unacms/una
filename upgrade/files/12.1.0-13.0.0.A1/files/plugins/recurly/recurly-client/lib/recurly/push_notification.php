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
   *    Account Notifications
   *      - new_account_notification
   *      - updated_account_notification
   *      - canceled_account_notification
   *      - billing_info_updated_notification
   *      - billing_info_update_failed_notification
   *      - new_shipping_address_notification
   *      - updated_shipping_address_notification
   *      - deleted_shipping_address_notification
   *    Subscription Notifications
   *      - new_subscription_notification
   *      - updated_subscription_notification
   *      - canceled_subscription_notification
   *      - expired_subscription_notification
   *      - renewed_subscription_notification
   *      - reactivated_account_notification
   *      - subscription_paused_notification
   *      - subscription_resumed_notification
   *      - scheduled_subscription_pause_notification
   *      - subscription_pause_modified_notification
   *      - paused_subscription_renewal_notification
   *      - subscription_pause_canceled_notification
   *    Usage Notifications
   *      - new_usage_notification
   *    Gift Card Notifications
   *      - purchased_gift_card_notification
   *      - canceled_gift_card_notification
   *      - updated_gift_card_notification
   *      - regenerated_gift_card_notification
   *      - redeemed_gift_card_notification
   *      - updated_balance_gift_card_notification
   *      - low_balance_gift_card_notification
   *    Charge Invoice Notifications
   *      - new_charge_invoice_notification
   *      - processing_charge_invoice_notification
   *      - past_due_charge_invoice_notification
   *      - paid_charge_invoice_notification
   *      - failed_charge_invoice_notification
   *      - reopened_charge_invoice_notification
   *      - updated_charge_invoice_notification
   *    Credit Invoice Notifications
   *      - new_credit_invoice_notification
   *      - processing_credit_invoice_notification
   *      - closed_credit_invoice_notification
   *      - voided_credit_invoice_notification
   *      - reopened_credit_invoice_notification
   *      - open_credit_invoice_notification
   *      - updated_credit_invoice_notification
   *    Invoice Notifications
   *      - new_invoice_notification
   *      - processing_invoice_notification
   *      - closed_invoice_notification
   *      - past_due_invoice_notification
   *      - updated_invoice_notification
   *    Payment Notifications
   *      - scheduled_payment_notification
   *      - processing_payment_notification
   *      - successful_payment_notification
   *      - failed_payment_notification
   *      - successful_refund_notification
   *      - void_payment_notification
   *      - fraud_info_updated_notification
   *      - transaction_status_updated_notification
   *      - transaction_authorized_notification
   *    Credit Payment Notifications
   *      - new_credit_payment_notification
   *      - voided_credit_payment_notification
   *    Dunning Event Notifications
   *      - new_dunning_event_notification
   */
  var $type;

  var $account;
  var $subscription;
  var $transaction;
  var $invoice;
  var $credit_payment;
  var $gift_card;
  var $shipping_address;
  var $usage;

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
        case 'credit_payment':
          $this->credit_payment = $child_node;
          break;
        case 'gift_card':
          $this->gift_card = $child_node;
          break;
        case 'shipping_address':
          $this->shipping_address = $child_node;
          break;
        case 'usage':
          $this->usage = $child_node;
          break;
      }
    }
  }
}
