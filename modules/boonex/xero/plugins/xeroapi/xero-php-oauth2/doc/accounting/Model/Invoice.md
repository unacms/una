# Invoice

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**type** | **string** | See Invoice Types | [optional] 
**contact** | [**\XeroAPI\XeroPHP\Models\Accounting\Contact**](Contact.md) |  | [optional] 
**line_items** | [**\XeroAPI\XeroPHP\Models\Accounting\LineItem[]**](LineItem.md) | See LineItems | [optional] 
**date** | **string** | Date invoice was issued – YYYY-MM-DD. If the Date element is not specified it will default to the current date based on the timezone setting of the organisation | [optional] 
**due_date** | **string** | Date invoice is due – YYYY-MM-DD | [optional] 
**line_amount_types** | [**\XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes**](LineAmountTypes.md) |  | [optional] 
**invoice_number** | **string** | ACCREC – Unique alpha numeric code identifying invoice (when missing will auto-generate from your Organisation Invoice Settings) (max length &#x3D; 255) | [optional] 
**reference** | **string** | ACCREC only – additional reference number | [optional] 
**branding_theme_id** | **string** | See BrandingThemes | [optional] 
**url** | **string** | URL link to a source document – shown as “Go to [appName]” in the Xero app | [optional] 
**currency_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**currency_rate** | **double** | The currency rate for a multicurrency invoice. If no rate is specified, the XE.com day rate is used. (max length &#x3D; [18].[6]) | [optional] 
**status** | **string** | See Invoice Status Codes | [optional] 
**sent_to_contact** | **bool** | Boolean to set whether the invoice in the Xero app should be marked as “sent”. This can be set only on invoices that have been approved | [optional] 
**expected_payment_date** | **string** | Shown on sales invoices (Accounts Receivable) when this has been set | [optional] 
**planned_payment_date** | **string** | Shown on bills (Accounts Payable) when this has been set | [optional] 
**cis_deduction** | **double** | CIS deduction for UK contractors | [optional] 
**cis_rate** | **double** | CIS Deduction rate for the organisation | [optional] 
**sub_total** | **double** | Total of invoice excluding taxes | [optional] 
**total_tax** | **double** | Total tax on invoice | [optional] 
**total** | **double** | Total of Invoice tax inclusive (i.e. SubTotal + TotalTax). This will be ignored if it doesn’t equal the sum of the LineAmounts | [optional] 
**total_discount** | **double** | Total of discounts applied on the invoice line items | [optional] 
**invoice_id** | **string** | Xero generated unique identifier for invoice | [optional] 
**repeating_invoice_id** | **string** | Xero generated unique identifier for repeating invoices | [optional] 
**has_attachments** | **bool** | boolean to indicate if an invoice has an attachment | [optional] [default to false]
**is_discounted** | **bool** | boolean to indicate if an invoice has a discount | [optional] 
**payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment[]**](Payment.md) | See Payments | [optional] 
**prepayments** | [**\XeroAPI\XeroPHP\Models\Accounting\Prepayment[]**](Prepayment.md) | See Prepayments | [optional] 
**overpayments** | [**\XeroAPI\XeroPHP\Models\Accounting\Overpayment[]**](Overpayment.md) | See Overpayments | [optional] 
**amount_due** | **double** | Amount remaining to be paid on invoice | [optional] 
**amount_paid** | **double** | Sum of payments received for invoice | [optional] 
**fully_paid_on_date** | **string** | The date the invoice was fully paid. Only returned on fully paid invoices | [optional] 
**amount_credited** | **double** | Sum of all credit notes, over-payments and pre-payments applied to invoice | [optional] 
**updated_date_utc** | **string** | Last modified date UTC format | [optional] 
**credit_notes** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNote[]**](CreditNote.md) | Details of credit notes that have been applied to an invoice | [optional] 
**attachments** | [**\XeroAPI\XeroPHP\Models\Accounting\Attachment[]**](Attachment.md) | Displays array of attachments from the API | [optional] 
**has_errors** | **bool** | A boolean to indicate if a invoice has an validation errors | [optional] [default to false]
**status_attribute_string** | **string** | A string to indicate if a invoice status | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 
**warnings** | [**\XeroAPI\XeroPHP\Models\Accounting\ValidationError[]**](ValidationError.md) | Displays array of warning messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


