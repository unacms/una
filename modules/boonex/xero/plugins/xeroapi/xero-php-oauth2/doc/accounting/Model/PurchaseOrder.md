# PurchaseOrder

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See LineItems | [optional] 
**date** | **string** | Date purchase order was issued – YYYY-MM-DD. If the Date element is not specified then it will default to the current date based on the timezone setting of the organisation | [optional] 
**delivery_date** | **string** | Date the goods are to be delivered – YYYY-MM-DD | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**purchase_order_number** | **string** | Unique alpha numeric code identifying purchase order (when missing will auto-generate from your Organisation Invoice Settings) | [optional] 
**reference** | **string** | Additional reference number | [optional] 
**branding_theme_id** | **string** | See BrandingThemes | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**status** | **string** | See Purchase Order Status Codes | [optional] 
**sent_to_contact** | **bool** | Boolean to set whether the purchase order should be marked as “sent”. This can be set only on purchase orders that have been approved or billed | [optional] 
**delivery_address** | **string** | The address the goods are to be delivered to | [optional] 
**attention_to** | **string** | The person that the delivery is going to | [optional] 
**telephone** | **string** | The phone number for the person accepting the delivery | [optional] 
**delivery_instructions** | **string** | A free text feild for instructions (500 characters max) | [optional] 
**expected_arrival_date** | **string** | The date the goods are expected to arrive. | [optional] 
**purchase_order_id** | **string** | Xero generated unique identifier for purchase order | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency purchase order. If no rate is specified, the XE.com day rate is used. | [optional] 
**sub_total** | **double** | Total of purchase order excluding taxes | [optional] 
**total_tax** | **double** | Total tax on purchase order | [optional] 
**total** | **double** | Total of Purchase Order tax inclusive (i.e. SubTotal + TotalTax) | [optional] 
**total_discount** | **double** | Total of discounts applied on the purchase order line items | [optional] 
**has_attachments** | **bool** | boolean to indicate if a purchase order has an attachment | [optional] [default to false]
**updated_date_utc** | **string** | Last modified date UTC format | [optional] 
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 
**warnings** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of warning messages from the API | [optional] 
**attachments** | [**\XeroAPI\XeroPHP\Models\Accounting\Attachment[]**](Attachment.md) | Displays array of attachments from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


