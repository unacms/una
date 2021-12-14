# Prepayment

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | See Prepayment Types | [optional] 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**date** | **string** | The date the prepayment is created YYYY-MM-DD | [optional] 
**status** | **string** | See Prepayment Status Codes | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See Prepayment Line Items | [optional] 
**sub_total** | **double** | The subtotal of the prepayment excluding taxes | [optional] 
**total_tax** | **double** | The total tax on the prepayment | [optional] 
**total** | **double** | The total of the prepayment(subtotal + total tax) | [optional] 
**reference** | **string** | Returns Invoice number field. Reference field isn&#39;t available. | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to the prepayment | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**prepayment_id** | **string** | Xero generated unique identifier | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency prepayment. If no rate is specified, the XE.com day rate is used | [optional] 
**remaining_credit** | **double** | The remaining credit balance on the prepayment | [optional] 
**allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocation[]**](Allocation.md) | See Allocations | [optional] 
**payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment[]**](Payment.md) | See Payments | [optional] 
**applied_amount** | **double** | The amount of applied to an invoice | [optional] 
**has_attachments** | **bool** | boolean to indicate if a prepayment has an attachment | [optional] [default to false]
**attachments** | [**\XeroAPI\XeroPHP\Models\Accounting\Attachment[]**](Attachment.md) | See Attachments | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


