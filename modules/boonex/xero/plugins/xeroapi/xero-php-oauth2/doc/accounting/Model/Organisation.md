# Organisation

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**organisation_id** | **string** | Unique Xero identifier | [optional] 
**api_key** | **string** | Display a unique key used for Xero-to-Xero transactions | [optional] 
**name** | **string** | Display name of organisation shown in Xero | [optional] 
**legal_name** | **string** | Organisation name shown on Reports | [optional] 
**pays_tax** | **bool** | Boolean to describe if organisation is registered with a local tax authority i.e. true, false | [optional] 
**version** | **string** | See Version Types | [optional] 
**organisation_type** | **string** | Organisation Type | [optional] 
**base_currency** | [**\XeroAPI\XeroPHP\Models\Accounting\CurrencyCode**](CurrencyCode.md) |  | [optional] 
**country_code** | [**\XeroAPI\XeroPHP\Models\Accounting\CountryCode**](CountryCode.md) |  | [optional] 
**is_demo_company** | **bool** | Boolean to describe if organisation is a demo company. | [optional] 
**organisation_status** | **string** | Will be set to ACTIVE if you can connect to organisation via the Xero API | [optional] 
**registration_number** | **string** | Shows for New Zealand, Australian and UK organisations | [optional] 
**employer_identification_number** | **string** | Shown if set. US Only. | [optional] 
**tax_number** | **string** | Shown if set. Displays in the Xero UI as Tax File Number (AU), GST Number (NZ), VAT Number (UK) and Tax ID Number (US &amp; Global). | [optional] 
**financial_year_end_day** | **int** | Calendar day e.g. 0-31 | [optional] 
**financial_year_end_month** | **int** | Calendar Month e.g. 1-12 | [optional] 
**sales_tax_basis** | **string** | The accounting basis used for tax returns. See Sales Tax Basis | [optional] 
**sales_tax_period** | **string** | The frequency with which tax returns are processed. See Sales Tax Period | [optional] 
**default_sales_tax** | **string** | The default for LineAmountTypes on sales transactions | [optional] 
**default_purchases_tax** | **string** | The default for LineAmountTypes on purchase transactions | [optional] 
**period_lock_date** | **string** | Shown if set. See lock dates | [optional] 
**end_of_year_lock_date** | **string** | Shown if set. See lock dates | [optional] 
**created_date_utc** | **string** | Timestamp when the organisation was created in Xero | [optional] 
**timezone** | [**\XeroAPI\XeroPHP\Models\Accounting\TimeZone**](TimeZone.md) |  | [optional] 
**organisation_entity_type** | **string** | Organisation Entity Type | [optional] 
**short_code** | **string** | A unique identifier for the organisation. Potential uses. | [optional] 
**class** | **string** | Organisation Classes describe which plan the Xero organisation is on (e.g. DEMO, TRIAL, PREMIUM) | [optional] 
**edition** | **string** | BUSINESS or PARTNER. Partner edition organisations are sold exclusively through accounting partners and have restricted functionality (e.g. no access to invoicing) | [optional] 
**line_of_business** | **string** | Description of business type as defined in Organisation settings | [optional] 
**addresses** | [**\XeroAPI\XeroPHP\Models\Accounting\AddressForOrganisation[]**](AddressForOrganisation.md) | Address details for organisation – see Addresses | [optional] 
**phones** | [**\XeroAPI\XeroPHP\Models\Accounting\Phone[]**](Phone.md) | Phones details for organisation – see Phones | [optional] 
**external_links** | [**\XeroAPI\XeroPHP\Models\Accounting\ExternalLink[]**](ExternalLink.md) | Organisation profile links for popular services such as Facebook,Twitter, GooglePlus and LinkedIn. You can also add link to your website here. Shown if Organisation settings  is updated in Xero. See ExternalLinks below | [optional] 
**payment_terms** | [**\XeroAPI\XeroPHP\Models\Accounting\PaymentTerm**](PaymentTerm.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


