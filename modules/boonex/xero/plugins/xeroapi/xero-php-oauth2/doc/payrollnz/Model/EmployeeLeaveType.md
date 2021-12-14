# EmployeeLeaveType

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**leave_type_id** | **string** | The Xero identifier for leave type | [optional] 
**schedule_of_accrual** | **string** | The schedule of accrual | [optional] 
**hours_accrued_annually** | **double** | The number of hours accrued for the leave annually. This is 0 when the scheduleOfAccrual chosen is \&quot;OnHourWorked\&quot; | [optional] 
**maximum_to_accrue** | **double** | The maximum number of hours that can be accrued for the leave | [optional] 
**opening_balance** | **double** | The initial number of hours assigned when the leave was added to the employee | [optional] 
**rate_accrued_hourly** | **double** | The number of hours added to the leave balance for every hour worked by the employee. This is normally 0, unless the scheduleOfAccrual chosen is \&quot;OnHourWorked\&quot; | [optional] 
**percentage_of_gross_earnings** | **double** | Specific for scheduleOfAccrual having percentage of gross earnings. Identifies how much percentage of gross earnings is accrued per pay period. | [optional] 
**include_holiday_pay_every_pay** | **bool** | Specific to Holiday pay. Flag determining if pay for leave type is added on each pay run. | [optional] 
**show_annual_leave_in_advance** | **bool** | Specific to Annual Leave. Flag to include leave available to take in advance in the balance in the payslip | [optional] 
**annual_leave_total_amount_paid** | **double** | Specific to Annual Leave. Annual leave balance in dollars | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


