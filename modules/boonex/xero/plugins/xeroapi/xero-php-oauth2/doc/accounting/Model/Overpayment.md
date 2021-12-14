# Overpayment

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | See Overpayment Types | [optional] 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**date** | **string** | The date the overpayment is created YYYY-MM-DD | [optional] 
**status** | **string** | See Overpayment Status Codes | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See Overpayment Line Items | [optional] 
**sub_total** | **double** | The subtotal of the overpayment excluding taxes | [optional] 
**total_tax** | **double** | The total tax on the overpayment | [optional] 
**total** | **double** | The total of the overpayment (subtotal + total tax) | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to the overpayment | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**overpayment_id** | **string** | Xero generated unique identifier | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency overpayment. If no rate is specified, the XE.com day rate is used | [optional] 
**remaining_credit** | **double** | The remaining credit balance on the overpayment | [optional] 
**allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocation[]**](Allocation.md) | See Allocations | [optional] 
**applied_amount** | **double** | The amount of applied to an invoice | [optional] 
**payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment[]**](Payment.md) | See Payments | [optional] 
**has_attachments** | **bool** | boolean to indicate if a overpayment has an attachment | [optional] [default to false]
**attachments** | [**\XeroAPI\XeroPHP\Models\Accounting\Attachment[]**](Attachment.md) | See Attachments | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


