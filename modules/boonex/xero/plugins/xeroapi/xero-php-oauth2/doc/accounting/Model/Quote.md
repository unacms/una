# Quote

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**quote_id** | **string** | QuoteID GUID is automatically generated and is returned after create or GET. | [optional] 
**quote_number** | **string** | Unique alpha numeric code identifying a quote (Max Length &#x3D; 255) | [optional] 
**reference** | **string** | Additional reference number | [optional] 
**terms** | **string** | Terms of the quote | [optional] 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See LineItems | [optional] 
**date** | **string** | Date quote was issued – YYYY-MM-DD. If the Date element is not specified it will default to the current date based on the timezone setting of the organisation | [optional] 
**date_string** | **string** | Date the quote was issued (YYYY-MM-DD) | [optional] 
**expiry_date** | **string** | Date the quote expires – YYYY-MM-DD. | [optional] 
**expiry_date_string** | **string** | Date the quote expires – YYYY-MM-DD. | [optional] 
**status** | [**\XeroAPI\XeroPHP\Models\Accounting\QuoteStatusCodes**](QuoteStatusCodes.md) |  | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency quote | [optional] 
**sub_total** | **double** | Total of quote excluding taxes. | [optional] 
**total_tax** | **double** | Total tax on quote | [optional] 
**total** | **double** | Total of Quote tax inclusive (i.e. SubTotal + TotalTax). This will be ignored if it doesn’t equal the sum of the LineAmounts | [optional] 
**total_discount** | **double** | Total of discounts applied on the quote line items | [optional] 
**title** | **string** | Title text for the quote | [optional] 
**summary** | **string** | Summary text for the quote | [optional] 
**branding_theme_id** | **string** | See BrandingThemes | [optional] 
**updated_date_utc** | **string** | Last modified date UTC format | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\QuoteLineAmountTypes**](QuoteLineAmountTypes.md) |  | [optional] 
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


