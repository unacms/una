<?php

/**
 * Class Recurly_Address
 * @property string $first_name The first name.
 * @property string $last_name The last name.
 * @property string $name_on_account The first and last name.
 * @property string $company The company.
 * @property string $address1 The first street address line.
 * @property string $address2 The second street address line.
 * @property string $city The city.
 * @property string $state The state/province. Required if country = US, CA, IT or NL.
 * @property string $zip The zip/postal code.
 * @property string $country The 2 character country code.
 * @property string $phone The phone number.
 */
class Recurly_Address extends Recurly_Resource {
  protected function getNodeName() {
    return 'address';
  }
  protected function getWriteableAttributes() {
    return array(
      'first_name', 'last_name', 'name_on_account', 'company',
      'address1', 'address2', 'city', 'state',
      'zip', 'country', 'phone'
    );
  }
}
