<?php

/**
 * Class Recurly_Delivery
 * @property string $method The delivery method. Allowed values: [email, post].
 * @property string $email_address The email address of the recipient.
 * @property string $first_name The first name of the recipient.
 * @property string $last_name The last name of the recipient.
 * @property string $gifter_name The name of the gifter for the purpose of message displays to the recipient.
 * @property string $personal_message The personal message from the gifter to the recipient. 255 characters.
 * @property Recurly_Address $address The address of the recipient. Required if method = post.
 * @property DateTime $deliver_at When the gift card should be delivered to the recipient. If null, the gift card will be delivered immediately. If a datetime is provided, the delivery will be in an hourly window, rounding down. For example, 6:23 pm will be in the 6:00 pm hourly batch.
 */
class Recurly_Delivery extends Recurly_Resource
{
  protected function getNodeName() {
    return 'delivery';
  }
  protected function getWriteableAttributes() {
    return array(
      'method','email_address','first_name','last_name',
      'address','gifter_name','personal_message','deliver_at'
    );
  }
}
