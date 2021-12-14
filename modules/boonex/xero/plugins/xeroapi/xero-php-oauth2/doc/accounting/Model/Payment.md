# Payment

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**invoice** | [**\XeroAPI\XeroPHP\Models\Accounting\Invoice**](Invoice.md) |  | [optional] 
**credit_note** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNote**](CreditNote.md) |  | [optional] 
**prepayment** | [**\XeroAPI\XeroPHP\Models\Accounting\Prepayment**](Prepayment.md) |  | [optional] 
**overpayment** | [**\XeroAPI\XeroPHP\Models\Accounting\Overpayment**](Overpayment.md) |  | [optional] 
**invoice_number** | **string** | Number of invoice or credit note you are applying payment to e.g.INV-4003 | [optional] 
**credit_note_number** | **string** | Number of invoice or credit note you are applying payment to e.g. INV-4003 | [optional] 
**account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](Account.md) |  | [optional] 
**code** | **string** | Code of account you are using to make the payment e.g. 001 (note- not all accounts have a code value) | [optional] 
**date** | **string** | Date the payment is being made (YYYY-MM-DD) e.g. 2009-09-06 | [optional] 
**currency_rate** | **double** | Exchange rate when payment is received. Only used for non base currency invoices and credit notes e.g. 0.7500 | [optional] 
**amount** | **double** | The amount of the payment. Must be less than or equal to the outstanding amount owing on the invoice e.g. 200.00 | [optional] 
**reference** | **string** | An optional description for the payment e.g. Direct Debit | [optional] 
**is_reconciled** | **bool** | An optional parameter for the payment. A boolean indicating whether you would like the payment to be created as reconciled when using PUT, or whether a payment has been reconciled when using GET | [optional] 
**status** | **string** | The status of the payment. | [optional] 
**payment_type** | **string** | See Payment Types. | [optional] 
**updated_date_utc** | **string** | UTC timestamp of last update to the payment | [optional] 
**payment_id** | **string** | The Xero identifier for an Payment e.g. 297c2dc5-cc47-4afd-8ec8-74990b8761e9 | [optional] 
**batch_payment_id** | **string** | Present if the payment was created as part of a batch. | [optional] 
**bank_account_number** | **string** | The suppliers bank account number the payment is being made to | [optional] 
**particulars** | **string** | The suppliers bank account number the payment is being made to | [optional] 
**details** | **string** | The information to appear on the supplier&#39;s bank account | [optional] 
**has_account** | **bool** | A boolean to indicate if a contact has an validation errors | [optional] [default to false]
**has_validation_errors** | **bool** | A boolean to indicate if a contact has an validation errors | [optional] [default to false]
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


