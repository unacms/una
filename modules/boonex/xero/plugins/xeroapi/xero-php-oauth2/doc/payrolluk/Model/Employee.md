# Employee

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**employee_id** | **string** | Xero unique identifier for the employee | [optional] 
**title** | **string** | Title of the employee | [optional] 
**first_name** | **string** | First name of employee | [optional] 
**last_name** | **string** | Last name of employee | [optional] 
**date_of_birth** | [**\DateTime**](\DateTime.md) | Date of birth of the employee (YYYY-MM-DD) | [optional] 
**address** | [**\XeroAPI\XeroPHP\Models\PayrollUk\Address**](Address.md) |  | [optional] 
**email** | **string** | The email address for the employee | [optional] 
**gender** | **string** | The employee’s gender | [optional] 
**phone_number** | **string** | Employee phone number | [optional] 
**start_date** | [**\DateTime**](\DateTime.md) | Employment start date of the employee at the time it was requested | [optional] 
**end_date** | [**\DateTime**](\DateTime.md) | Employment end date of the employee at the time it was requested | [optional] 
**payroll_calendar_id** | **string** | Xero unique identifier for the payroll calendar of the employee | [optional] 
**updated_date_utc** | [**\DateTime**](\DateTime.md) | UTC timestamp of last update to the employee | [optional] 
**created_date_utc** | [**\DateTime**](\DateTime.md) | UTC timestamp when the employee was created in Xero | [optional] 
**national_insurance_number** | **string** | National insurance number of the employee | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


