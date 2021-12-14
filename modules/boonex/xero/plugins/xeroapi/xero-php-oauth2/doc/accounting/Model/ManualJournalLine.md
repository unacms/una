# ManualJournalLine

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**line_amount** | **double** | total for line. Debits are positive, credits are negative value | [optional] 
**account_code** | **string** | See Accounts | [optional] 
**account_id** | **string** | See Accounts | [optional] 
**description** | **string** | Description for journal line | [optional] 
**tax_type** | **string** | The tax type from TaxRates | [optional] 
**tracking** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategory[]**](TrackingCategory.md) | Optional Tracking Category – see Tracking. Any JournalLine can have a maximum of 2 &lt;TrackingCategory&gt; elements. | [optional] 
**tax_amount** | **double** | The calculated tax amount based on the TaxType and LineAmount | [optional] 
**is_blank** | **bool** | is the line blank | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


