# CreditNote

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | See Credit Note Types | [optional] 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**date** | **string** | The date the credit note is issued YYYY-MM-DD. If the Date element is not specified then it will default to the current date based on the timezone setting of the organisation | [optional] 
**due_date** | **string** | Date invoice is due – YYYY-MM-DD | [optional] 
**status** | **string** | See Credit Note Status Codes | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See Invoice Line Items | [optional] 
**sub_total** | **double** | The subtotal of the credit note excluding taxes | [optional] 
**total_tax** | **double** | The total tax on the credit note | [optional] 
**total** | **double** | The total of the Credit Note(subtotal + total tax) | [optional] 
**cis_deduction** | **double** | CIS deduction for UK contractors | [optional] 
**cis_rate** | **double** | CIS Deduction rate for the organisation | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to the credit note | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**fully_paid_on_date** | **string** | Date when credit note was fully paid(UTC format) | [optional] 
**credit_note_id** | **string** | Xero generated unique identifier | [optional] 
**credit_note_number** | **string** | ACCRECCREDIT – Unique alpha numeric code identifying credit note (when missing will auto-generate from your Organisation Invoice Settings) | [optional] 
**reference** | **string** | ACCRECCREDIT only – additional reference number | [optional] 
**sent_to_contact** | **bool** | boolean to indicate if a credit note has been sent to a contact via  the Xero app (currently read only) | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency invoice. If no rate is specified, the XE.com day rate is used | [optional] 
**remaining_credit** | **double** | The remaining credit balance on the Credit Note | [optional] 
**allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocation[]**](Allocation.md) | See Allocations | [optional] 
**applied_amount** | **double** | The amount of applied to an invoice | [optional] 
**payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment[]**](Payment.md) | See Payments | [optional] 
**branding_theme_id** | **string** | See BrandingThemes | [optional] 
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**has_attachments** | **bool** | boolean to indicate if a credit note has an attachment | [optional] [default to false]
**has_errors** | **bool** | A boolean to indicate if a credit note has an validation errors | [optional] [default to false]
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 
**warnings** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of warning messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


