# EmployeeLeaveType

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**leave_type_id** | **string** | The Xero identifier for leave type | 
**schedule_of_accrual** | **string** | The schedule of accrual | 
**hours_accrued_annually** | **double** | The number of hours accrued for the leave annually. This is 0 when the scheduleOfAccrual chosen is \&quot;OnHourWorked\&quot; | [optional] 
**maximum_to_accrue** | **double** | The maximum number of hours that can be accrued for the leave | [optional] 
**opening_balance** | **double** | The initial number of hours assigned when the leave was added to the employee | [optional] 
**rate_accrued_hourly** | **double** | The number of hours added to the leave balance for every hour worked by the employee. This is normally 0, unless the scheduleOfAccrual chosen is \&quot;OnHourWorked\&quot; | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


