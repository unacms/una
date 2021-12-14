# Allocation

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**invoice** | [**\XeroAPI\XeroPHP\Models\Accounting\Invoice**](Invoice.md) |  | 
**overpayment** | [**\XeroAPI\XeroPHP\Models\Accounting\Overpayment**](Overpayment.md) |  | [optional] 
**prepayment** | [**\XeroAPI\XeroPHP\Models\Accounting\Prepayment**](Prepayment.md) |  | [optional] 
**credit_note** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNote**](CreditNote.md) |  | [optional] 
**amount** | **double** | the amount being applied to the invoice | 
**date** | **string** | the date the allocation is applied YYYY-MM-DD. | 
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


