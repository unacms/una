# BankTransaction

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | See Bank Transaction Types | 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See LineItems | 
**bank_account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](Account.md) |  | 
**is_reconciled** | **bool** | Boolean to show if transaction is reconciled | [optional] 
**date** | **string** | Date of transaction – YYYY-MM-DD | [optional] 
**reference** | **string** | Reference for the transaction. Only supported for SPEND and RECEIVE transactions. | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**currency_rate** | **double** | Exchange rate to base currency when money is spent or received. e.g.0.7500 Only used for bank transactions in non base currency. If this isn’t specified for non base currency accounts then either the user-defined rate (preference) or the XE.com day rate will be used. Setting currency is only supported on overpayments. | [optional] 
**url** | **string** | URL link to a source document – shown as “Go to App Name” | [optional] 
**status** | **string** | See Bank Transaction Status Codes | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**sub_total** | **double** | Total of bank transaction excluding taxes | [optional] 
**total_tax** | **double** | Total tax on bank transaction | [optional] 
**total** | **double** | Total of bank transaction tax inclusive | [optional] 
**bank_transaction_id** | **string** | Xero generated unique identifier for bank transaction | [optional] 
**prepayment_id** | **string** | Xero generated unique identifier for a Prepayment. This will be returned on BankTransactions with a Type of SPEND-PREPAYMENT or RECEIVE-PREPAYMENT | [optional] 
**overpayment_id** | **string** | Xero generated unique identifier for an Overpayment. This will be returned on BankTransactions with a Type of SPEND-OVERPAYMENT or RECEIVE-OVERPAYMENT | [optional] 
**updated_date_utc** | **string** | Last modified date UTC format | [optional] 
**has_attachments** | **bool** | Boolean to indicate if a bank transaction has an attachment | [optional] [default to false]
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


