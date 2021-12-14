# Contact

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contact_id** | **string** | Xero identifier | [optional] 
**contact_number** | **string** | This can be updated via the API only i.e. This field is read only on the Xero contact screen, used to identify contacts in external systems (max length &#x3D; 50). If the Contact Number is used, this is displayed as Contact Code in the Contacts UI in Xero. | [optional] 
**account_number** | **string** | A user defined account number. This can be updated via the API and the Xero UI (max length &#x3D; 50) | [optional] 
**contact_status** | **string** | Current status of a contact – see contact status types | [optional] 
**name** | **string** | Full name of contact/organisation (max length &#x3D; 255) | [optional] 
**first_name** | **string** | First name of contact person (max length &#x3D; 255) | [optional] 
**last_name** | **string** | Last name of contact person (max length &#x3D; 255) | [optional] 
**email_address** | **string** | Email address of contact person (umlauts not supported) (max length  &#x3D; 255) | [optional] 
**skype_user_name** | **string** | Skype user name of contact | [optional] 
**contact_persons** | [**\XeroAPI\XeroPHP\Models\Accounting\ContactPerson[]**](ContactPerson.md) | See contact persons | [optional] 
**bank_account_details** | **string** | Bank account number of contact | [optional] 
**tax_number** | **string** | Tax number of contact – this is also known as the ABN (Australia), GST Number (New Zealand), VAT Number (UK) or Tax ID Number (US and global) in the Xero UI depending on which regionalized version of Xero you are using (max length &#x3D; 50) | [optional] 
**accounts_receivable_tax_type** | **string** | The tax type from TaxRates | [optional] 
**accounts_payable_tax_type** | **string** | The tax type from TaxRates | [optional] 
**addresses** | [**\XeroAPI\XeroPHP\Models\Accounting\Address[]**](Address.md) | Store certain address types for a contact – see address types | [optional] 
**phones** | [**\XeroAPI\XeroPHP\Models\Accounting\Phone[]**](Phone.md) | Store certain phone types for a contact – see phone types | [optional] 
**is_supplier** | **bool** | true or false – Boolean that describes if a contact that has any AP  invoices entered against them. Cannot be set via PUT or POST – it is automatically set when an accounts payable invoice is generated against this contact. | [optional] 
**is_customer** | **bool** | true or false – Boolean that describes if a contact has any AR invoices entered against them. Cannot be set via PUT or POST – it is automatically set when an accounts receivable invoice is generated against this contact. | [optional] 
**default_currency** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**xero_network_key** | **string** | Store XeroNetworkKey for contacts. | [optional] 
**sales_default_account_code** | **string** | The default sales account code for contacts | [optional] 
**purchases_default_account_code** | **string** | The default purchases account code for contacts | [optional] 
**sales_tracking_categories** | [**\XeroAPI\XeroPHP\Models\Accounting\SalesTrackingCategory[]**](SalesTrackingCategory.md) | The default sales tracking categories for contacts | [optional] 
**purchases_tracking_categories** | [**\XeroAPI\XeroPHP\Models\Accounting\SalesTrackingCategory[]**](SalesTrackingCategory.md) | The default purchases tracking categories for contacts | [optional] 
**tracking_category_name** | **string** | The name of the Tracking Category assigned to the contact under SalesTrackingCategories and PurchasesTrackingCategories | [optional] 
**tracking_category_option** | **string** | The name of the Tracking Option assigned to the contact under SalesTrackingCategories and PurchasesTrackingCategories | [optional] 
**payment_terms** | [**\XeroAPI\XeroPHP\Models\Accounting\PaymentTerm**](PaymentTerm.md) |  | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to contact | [optional] 
**contact_groups** | [**\XeroAPI\XeroPHP\Models\Accounting\ContactGroup[]**](ContactGroup.md) | Displays which contact groups a contact is included in | [optional] 
**website** | **string** | Website address for contact (read only) | [optional] 
**branding_theme** | [**\XeroAPI\XeroPHP\Models\Accounting\BrandingTheme**](BrandingTheme.md) |  | [optional] 
**batch_payments** | [**\XeroAPI\XeroPHP\Models\Accounting\BatchPaymentDetails**](BatchPaymentDetails.md) |  | [optional] 
**discount** | **double** | The default discount rate for the contact (read only) | [optional] 
**balances** | [**\XeroAPI\XeroPHP\Models\Accounting\Balances**](Balances.md) |  | [optional] 
**attachments** | [**\XeroAPI\XeroPHP\Models\Accounting\Attachment[]**](Attachment.md) | Displays array of attachments from the API | [optional] 
**has_attachments** | **bool** | A boolean to indicate if a contact has an attachment | [optional] [default to false]
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays validation errors returned from the API | [optional] 
**has_validation_errors** | **bool** | A boolean to indicate if a contact has an validation errors | [optional] [default to false]
**status_attribute_string** | **string** | Status of object | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


