# EmployeeLeaveSetup

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**include_holiday_pay** | **bool** | Identifier if holiday pay will be included in each payslip | [optional] 
**holiday_pay_opening_balance** | **double** | Initial holiday pay balance. A percentage — usually 8% — of gross earnings since their last work anniversary. | [optional] 
**annual_leave_opening_balance** | **double** | Initial annual leave balance. The balance at their last anniversary, less any leave taken since then and excluding accrued annual leave. | [optional] 
**negative_annual_leave_balance_paid_amount** | **double** | The dollar value of annual leave opening balance if negative. | [optional] 
**sick_leave_hours_to_accrue_annually** | **double** | Number of hours accrued annually for sick leave. Multiply the number of days they&#39;re entitled to by the hours worked per day | [optional] 
**sick_leave_maximum_hours_to_accrue** | **double** | Maximum number of hours accrued annually for sick leave. Multiply the maximum days they can accrue by the hours worked per day | [optional] 
**sick_leave_opening_balance** | **double** | Initial sick leave balance. This will be positive unless they&#39;ve taken sick leave in advance | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


