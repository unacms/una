# BatchPayment

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](Account.md) |  | [optional] 
**reference** | **string** | (NZ Only) Optional references for the batch payment transaction. It will also show with the batch payment transaction in the bank reconciliation Find &amp; Match screen. Depending on your individual bank, the detail may also show on the bank statement you import into Xero. | [optional] 
**particulars** | **string** | (NZ Only) Optional references for the batch payment transaction. It will also show with the batch payment transaction in the bank reconciliation Find &amp; Match screen. Depending on your individual bank, the detail may also show on the bank statement you import into Xero. | [optional] 
**code** | **string** | (NZ Only) Optional references for the batch payment transaction. It will also show with the batch payment transaction in the bank reconciliation Find &amp; Match screen. Depending on your individual bank, the detail may also show on the bank statement you import into Xero. | [optional] 
**details** | **string** | (Non-NZ Only) These details are sent to the org’s bank as a reference for the batch payment transaction. They will also show with the batch payment transaction in the bank reconciliation Find &amp; Match screen. Depending on your individual bank, the detail may also show on the bank statement imported into Xero. Maximum field length &#x3D; 18 | [optional] 
**narrative** | **string** | (UK Only) Only shows on the statement line in Xero. Max length &#x3D;18 | [optional] 
**batch_payment_id** | **string** | The Xero generated unique identifier for the bank transaction (read-only) | [optional] 
**date_string** | **string** | Date the payment is being made (YYYY-MM-DD) e.g. 2009-09-06 | [optional] 
**date** | **string** | Date the payment is being made (YYYY-MM-DD) e.g. 2009-09-06 | [optional] 
**amount** | **double** | The amount of the payment. Must be less than or equal to the outstanding amount owing on the invoice e.g. 200.00 | [optional] 
**payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment[]**](Payment.md) | An array of payments | [optional] 
**type** | **string** | PAYBATCH for bill payments or RECBATCH for sales invoice payments (read-only) | [optional] 
**status** | **string** | AUTHORISED or DELETED (read-only). New batch payments will have a status of AUTHORISED. It is not possible to delete batch payments via the API. | [optional] 
**total_amount** | **string** | The total of the payments that make up the batch (read-only) | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to the payment | [optional] 
**is_reconciled** | **string** | Booelan that tells you if the batch payment has been reconciled (read-only) | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


