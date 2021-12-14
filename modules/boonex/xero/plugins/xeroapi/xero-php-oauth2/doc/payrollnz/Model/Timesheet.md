# Timesheet

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**timesheet_id** | **string** | The Xero identifier for a Timesheet | [optional] 
**payroll_calendar_id** | **string** | The Xero identifier for the Payroll Calendar that the Timesheet applies to | 
**employee_id** | **string** | The Xero identifier for the Employee that the Timesheet is for | 
**start_date** | [**\DateTime**](\DateTime.md) | The Start Date of the Timesheet period (YYYY-MM-DD) | 
**end_date** | [**\DateTime**](\DateTime.md) | The End Date of the Timesheet period (YYYY-MM-DD) | 
**status** | **string** | Status of the timesheet | [optional] 
**total_hours** | **double** | The Total Hours of the Timesheet | [optional] 
**updated_date_utc** | [**\DateTime**](\DateTime.md) | The UTC date time that the Timesheet was last updated | [optional] 
**timesheet_lines** | [**\XeroAPI\XeroPHP\Models\PayrollNz\TimesheetLine[]**](TimesheetLine.md) |  | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


