# BankTransfer

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**from_bank_account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](Account.md) |  | 
**to_bank_account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](Account.md) |  | 
**amount** | **double** | amount of the transaction | 
**date** | **string** | The date of the Transfer YYYY-MM-DD | [optional] 
**bank_transfer_id** | **string** | The identifier of the Bank Transfer | [optional] 
**currency_rate** | **double** | The currency rate | [optional] 
**from_bank_transaction_id** | **string** | The Bank Transaction ID for the source account | [optional] 
**to_bank_transaction_id** | **string** | The Bank Transaction ID for the destination account | [optional] 
**has_attachments** | **bool** | Boolean to indicate if a Bank Transfer has an attachment | [optional] [default to false]
**created_date_utc** | **string** | UTC timestamp of creation date of bank transfer | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


