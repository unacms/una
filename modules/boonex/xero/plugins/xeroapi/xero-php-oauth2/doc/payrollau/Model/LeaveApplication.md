# LeaveApplication

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**leave_application_id** | **string** | The Xero identifier for Payroll Employee | [optional] 
**employee_id** | **string** | The Xero identifier for Payroll Employee | [optional] 
**leave_type_id** | **string** | The Xero identifier for Leave Type | [optional] 
**title** | **string** | The title of the leave | [optional] 
**start_date** | **string** | Start date of the leave (YYYY-MM-DD) | [optional] 
**end_date** | **string** | End date of the leave (YYYY-MM-DD) | [optional] 
**description** | **string** | The Description of the Leave | [optional] 
**leave_periods** | [**\XeroAPI\XeroPHP\Models\PayrollAu\LeavePeriod[]**](LeavePeriod.md) |  | [optional] 
**updated_date_utc** | **string** | Last modified timestamp | [optional] 
**validation_errors** | [**\XeroAPI\XeroPHP\Models\PayrollAu\ValidationError[]**](ValidationError.md) | Displays array of validation error messages from the API | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


