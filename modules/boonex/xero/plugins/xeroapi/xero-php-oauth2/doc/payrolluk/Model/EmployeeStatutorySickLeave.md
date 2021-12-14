# EmployeeStatutorySickLeave

## Properties
Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**statutory_leave_id** | **string** | The unique identifier (guid) of a statutory leave | [optional] 
**employee_id** | **string** | The unique identifier (guid) of the employee | 
**leave_type_id** | **string** | The unique identifier (guid) of the \&quot;Statutory Sick Leave (non-pensionable)\&quot; pay item | 
**start_date** | [**\DateTime**](\DateTime.md) | The date when the leave starts | 
**end_date** | [**\DateTime**](\DateTime.md) | The date when the leave ends | 
**type** | **string** | the type of statutory leave | [optional] 
**status** | **string** | the type of statutory leave | [optional] 
**work_pattern** | **string[]** | The days of the work week the employee is scheduled to work at the time the leave is taken | 
**is_pregnancy_related** | **bool** | Whether the sick leave was pregnancy related | 
**sufficient_notice** | **bool** | Whether the employee provided sufficient notice and documentation as required by the employer supporting the sick leave request | 
**is_entitled** | **bool** | Whether the leave was entitled to receive payment | [optional] 
**entitlement_weeks_requested** | **double** | The amount of requested time (in weeks) | [optional] 
**entitlement_weeks_qualified** | **double** | The amount of statutory sick leave time off (in weeks) that is available to take at the time the leave was requested | [optional] 
**entitlement_weeks_remaining** | **double** | A calculated amount of time (in weeks) that remains for the statutory sick leave period | [optional] 
**overlaps_with_other_leave** | **bool** | Whether another leave (Paternity, Shared Parental specifically) occurs during the requested leave&#39;s period. While this is allowed it could affect payment amounts | [optional] 
**entitlement_failure_reasons** | **string[]** | If the leave requested was considered \&quot;not entitled\&quot;, the reasons why are listed here. | [optional] 

[[Back to Model list]](../README.md#documentation-for-models) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to README]](../README.md)


