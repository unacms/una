# Timesheet

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**employee_id** | **string** | The Xero identifier for an employee | 
**start_date** | **string** | Period start date (YYYY-MM-DD) | 
**end_date** | **string** | Period end date (YYYY-MM-DD) | 
**status** | [**\XeroAPI\XeroPHP\Models\PayrollAu\TimesheetStatus**](TimesheetStatus.md) |  | [optional] 
**hours** | **double** | Timesheet total hours | [optional] 
**timesheet_id** | **string** | The Xero identifier for a Payroll Timesheet | [optional] 
**timesheet_lines** | [**\XeroAPI\XeroPHP\Models\PayrollAu\TimesheetLine[]**](TimesheetLine.md) |  | [optional] 
**updated_date_utc** | **string** | Last modified timestamp | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\PayrollAu\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


