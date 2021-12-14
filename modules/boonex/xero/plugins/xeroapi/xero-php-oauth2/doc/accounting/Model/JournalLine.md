# JournalLine

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**journal_line_id** | **string** | Xero identifier for Journal | [optional] 
**account_id** | **string** | See Accounts | [optional] 
**account_code** | **string** | See Accounts | [optional] 
**account_type** | [**\XeroAPI\XeroPHP\Models\Accounting\AccountType**](AccountType.md) |  | [optional] 
**account_name** | **string** | See AccountCodes | [optional] 
**description** | **string** | The description from the source transaction line item. Only returned if populated. | [optional] 
**net_amount** | **double** | Net amount of journal line. This will be a positive value for a debit and negative for a credit | [optional] 
**gross_amount** | **double** | Gross amount of journal line (NetAmount + TaxAmount). | [optional] 
**tax_amount** | **double** | Total tax on a journal line | [optional] 
**tax_type** | **string** | The tax type from TaxRates | [optional] 
**tax_name** | **string** | see TaxRates | [optional] 
**tracking_categories** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategory[]**](TrackingCategory.md) | Optional Tracking Category – see Tracking. Any JournalLine can have a maximum of 2 &lt;TrackingCategory&gt; elements. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


